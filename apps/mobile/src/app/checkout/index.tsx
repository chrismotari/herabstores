import type { DeliveryZone, Order, PlaceOrderInput } from '@herabs/shared';
import { formatPrice } from '@herabs/shared';
import { useQuery, useQueryClient } from '@tanstack/react-query';
import { router } from 'expo-router';
import { openBrowserAsync } from 'expo-web-browser';
import { useState } from 'react';
import { Pressable, StyleSheet, View } from 'react-native';

import { Totals } from '@/components/cart/totals';
import { ScreenState } from '@/components/screen-state';
import { ThemedText } from '@/components/themed-text';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { FormScreen } from '@/components/ui/form-screen';
import { TextField } from '@/components/ui/text-field';
import { Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';
import { api, unwrap } from '@/lib/api';
import { cartQuery, placedOrderKey, useCart } from '@/lib/cart';
import { SITE_URL } from '@/lib/config';
import { useSession, type FieldErrors } from '@/lib/session';

type Fields = Pick<PlaceOrderInput, 'fname' | 'lname' | 'phone' | 'address' | 'city'> & {
  alt_phone: string;
  email: string;
  message: string;
};

const zonesQuery = {
  queryKey: ['delivery-zones'],
  queryFn: async (): Promise<DeliveryZone[]> => unwrap(await api.GET('/delivery-zones')).data,
};

export default function CheckoutScreen() {
  const colors = useTheme();
  const queryClient = useQueryClient();
  const { user } = useSession();
  const cart = useCart();
  const zones = useQuery(zonesQuery);

  const [fields, setFields] = useState<Fields>({
    fname: user?.fname ?? '',
    lname: user?.lname ?? '',
    phone: user?.phone ?? '',
    alt_phone: '',
    email: user?.email ?? '',
    address: user?.address ?? '',
    city: user?.city ?? 'Nairobi',
    message: '',
  });
  const [zoneChoice, setZoneChoice] = useState<string | null>(null);
  const [consented, setConsented] = useState(false);
  const [acceptTerms, setAcceptTerms] = useState(false);
  const [busy, setBusy] = useState(false);
  const [errors, setErrors] = useState<FieldErrors>({});

  if (!cart.data || !zones.data) {
    return <ScreenState error={cart.error ?? zones.error} onRetry={() => void (cart.refetch(), zones.refetch())} />;
  }

  const c = cart.data;
  if (c.items.length === 0) {
    return (
      <FormScreen>
        <ThemedText themeColor="textSecondary">Your cart is empty.</ThemedText>
        <Button title="Start shopping" onPress={() => router.dismissTo('/shop')} />
      </FormScreen>
    );
  }

  const zoneId = zoneChoice ?? user?.delivery_zone_id ?? (zones.data.length === 1 ? zones.data[0].id : '');
  const zone = zones.data.find((z) => z.id === zoneId);
  const discount = c.discount && !c.discount.error ? c.discount.amount : 0;

  const field = (name: keyof Fields) => ({
    value: fields[name],
    onChangeText: (value: string) => setFields((f) => ({ ...f, [name]: value })),
    error: errors[name]?.[0],
  });
  const optional = (value: string) => value.trim() || null;

  async function placeOrder() {
    setBusy(true);
    setErrors({});
    const { data, error } = await api.POST('/orders', {
      body: {
        fname: fields.fname.trim(),
        lname: fields.lname.trim(),
        phone: fields.phone.trim(),
        alt_phone: optional(fields.alt_phone),
        email: optional(fields.email),
        address: fields.address.trim(),
        city: fields.city.trim(),
        delivery_zone_id: zoneId,
        message: optional(fields.message),
        consented: consented ? '1' : null,
        accept_terms: acceptTerms || undefined, // the API requires it to be accepted
      },
    });
    setBusy(false);

    if (data) {
      const order: Order = data.data;
      queryClient.setQueryData(placedOrderKey(order.order_number), order);
      await queryClient.invalidateQueries({ queryKey: cartQuery.queryKey });
      await queryClient.invalidateQueries({ queryKey: ['products'] }); // stock changed
      router.replace({ pathname: '/checkout/confirmation', params: { order: order.order_number } });
      return;
    }
    const body = error as { errors?: FieldErrors; message?: string } | undefined;
    setErrors(body?.errors ?? { form: [body?.message ?? 'Something went wrong. Please try again.'] });
  }

  const firstError = Object.values(errors)[0]?.[0];

  return (
    <FormScreen>
      <ThemedText type="smallBold" style={styles.heading}>
        Delivery details
      </ThemedText>
      <TextField label="First name" autoComplete="given-name" textContentType="givenName" {...field('fname')} />
      <TextField label="Last name" autoComplete="family-name" textContentType="familyName" {...field('lname')} />
      <TextField label="Phone" keyboardType="phone-pad" autoComplete="tel" placeholder="07XX XXX XXX" {...field('phone')} />
      <TextField label="Alternative phone (optional)" keyboardType="phone-pad" {...field('alt_phone')} />
      <TextField
        label="Email (optional, for your receipt)"
        autoCapitalize="none"
        keyboardType="email-address"
        autoComplete="email"
        {...field('email')}
      />
      <TextField
        label="Address"
        placeholder="Estate, street, building, house number"
        autoComplete="street-address"
        multiline
        {...field('address')}
      />
      <TextField label="City / town" autoComplete="postal-address-locality" {...field('city')} />

      <ThemedText type="smallBold" style={styles.heading}>
        Delivery area
      </ThemedText>
      <View style={styles.zones} accessibilityRole="radiogroup">
        {zones.data.map((z) => {
          const selected = z.id === zoneId;
          return (
            <Pressable
              key={z.id}
              accessibilityRole="radio"
              accessibilityState={{ checked: selected }}
              onPress={() => setZoneChoice(z.id)}
              style={StyleSheet.flatten([
                styles.zone,
                { borderColor: selected ? colors.primary : colors.border },
                selected && { backgroundColor: colors.backgroundSelected },
              ])}>
              <View style={styles.flex}>
                <ThemedText type="smallBold">{z.name}</ThemedText>
                {!!z.description && (
                  <ThemedText type="small" themeColor="textSecondary">
                    {z.description}
                  </ThemedText>
                )}
              </View>
              <ThemedText type="smallBold">{formatPrice(z.delivery_fee)}</ThemedText>
            </Pressable>
          );
        })}
      </View>
      {errors.delivery_zone_id && (
        <ThemedText type="small" themeColor="danger">
          {errors.delivery_zone_id[0]}
        </ThemedText>
      )}

      <TextField
        label="Notes for the rider (optional)"
        multiline
        placeholder="Landmarks, gate code, best time to call"
        {...field('message')}
      />

      <ThemedText type="smallBold" style={styles.heading}>
        Order summary
      </ThemedText>
      {c.items.map((item) => (
        <View key={item.id} style={styles.summaryLine}>
          <ThemedText style={styles.flex} numberOfLines={2}>
            {item.quantity} × {(item.product ?? item.basket)?.name}
          </ThemedText>
          <ThemedText>{formatPrice(item.line_total)}</ThemedText>
        </View>
      ))}
      <Totals
        lines={[
          { label: 'Subtotal', amount: c.subtotal },
          ...(discount > 0 ? [{ label: `Discount (${c.discount?.code})`, amount: discount, negative: true }] : []),
          zone
            ? { label: 'Delivery', amount: zone.delivery_fee }
            : { label: 'Delivery', amount: 0, note: 'Choose an area' },
        ]}
        total={c.total + (zone?.delivery_fee ?? 0)}
        totalLabel="Pay on delivery"
      />
      {c.discount?.error && (
        <ThemedText type="small" themeColor="danger">
          {c.discount.error}
        </ThemedText>
      )}

      <Checkbox checked={consented} onChange={setConsented}>
        <ThemedText type="small">Send me news and offers</ThemedText>
      </Checkbox>
      <Checkbox checked={acceptTerms} onChange={setAcceptTerms} error={errors.accept_terms?.[0]}>
        <ThemedText type="small">
          I accept the{' '}
          <ThemedText
            type="small"
            themeColor="primary"
            onPress={() => void openBrowserAsync(`${SITE_URL}/terms-and-conditions`)}
            accessibilityRole="link">
            terms and conditions
          </ThemedText>
        </ThemedText>
      </Checkbox>

      {firstError && (
        <ThemedText themeColor="danger">
          {errors.form?.[0] ?? 'Please check the highlighted fields.'}
        </ThemedText>
      )}
      <Button title="Place order" onPress={placeOrder} loading={busy} />
    </FormScreen>
  );
}

const styles = StyleSheet.create({
  heading: { textTransform: 'uppercase', letterSpacing: 1, marginTop: Spacing.two },
  zones: { gap: Spacing.two },
  zone: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: Spacing.three,
    borderWidth: 1,
    borderRadius: Spacing.two,
    padding: Spacing.three,
  },
  flex: { flex: 1 },
  summaryLine: { flexDirection: 'row', gap: Spacing.three },
});
