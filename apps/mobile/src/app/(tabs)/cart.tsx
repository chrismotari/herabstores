import { formatPrice, type CartItem } from '@herabs/shared';
import { Link, router } from 'expo-router';
import { useState } from 'react';
import { Pressable, RefreshControl, ScrollView, StyleSheet, View } from 'react-native';

import { DiscountForm } from '@/components/cart/discount-form';
import { QuantityStepper } from '@/components/cart/quantity-stepper';
import { Totals } from '@/components/cart/totals';
import { ProductImage } from '@/components/catalog/product-image';
import { ScreenState } from '@/components/screen-state';
import { ThemedText } from '@/components/themed-text';
import { Button } from '@/components/ui/button';
import { MaxContentWidth, Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';
import { useTopInset } from '@/hooks/use-top-inset';
import { useCart, useCartActions } from '@/lib/cart';

export default function CartScreen() {
  const colors = useTheme();
  const topInset = useTopInset();
  const cart = useCart();

  if (!cart.data) return <ScreenState error={cart.error} onRetry={() => void cart.refetch()} />;

  const c = cart.data;

  return (
    <ScrollView
      contentInsetAdjustmentBehavior="automatic"
      keyboardShouldPersistTaps="handled"
      style={{ backgroundColor: colors.background }}
      contentContainerStyle={[styles.content, { paddingTop: topInset + Spacing.three }]}
      refreshControl={<RefreshControl refreshing={cart.isRefetching} onRefresh={() => void cart.refetch()} />}>
      <ThemedText type="subtitle">Your cart</ThemedText>

      {c.items.length === 0 ? (
        <View style={styles.empty}>
          <ThemedText themeColor="textSecondary">Your cart is empty.</ThemedText>
          <Button title="Start shopping" onPress={() => router.navigate('/shop')} />
        </View>
      ) : (
        <>
          <View>
            {c.items.map((item) => (
              <CartLine key={item.id} item={item} />
            ))}
          </View>

          <DiscountForm cart={c} />

          <Totals
            lines={[
              { label: 'Subtotal', amount: c.subtotal },
              ...(c.discount && c.discount.amount > 0
                ? [{ label: 'Discount', amount: c.discount.amount, negative: true }]
                : []),
              { label: 'Delivery', amount: 0, note: 'Chosen at checkout' },
            ]}
            total={c.total}
          />
          <ThemedText type="small" themeColor="textSecondary">
            You pay when your order is delivered.
          </ThemedText>
          <Button title="Checkout" onPress={() => router.push('/checkout')} />
        </>
      )}
    </ScrollView>
  );
}

function CartLine({ item }: { item: CartItem }) {
  const colors = useTheme();
  const { update, remove } = useCartActions();
  const [busy, setBusy] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const thing = item.product ?? item.basket;
  const max = Math.max(item.quantity, thing?.available_quantity ?? item.quantity);
  const href = item.product
    ? ({ pathname: '/products/[slug]', params: { slug: item.product.slug } } as const)
    : ({ pathname: '/baskets/[slug]', params: { slug: item.basket?.slug ?? item.basket?.id ?? '' } } as const);

  async function run(call: () => ReturnType<typeof remove>) {
    setBusy(true);
    setError(null);
    const result = await call();
    setBusy(false);
    if (!result.ok) setError(result.message);
  }

  return (
    <View style={[styles.line, { borderColor: colors.border, opacity: busy ? 0.6 : 1 }]}>
      <ProductImage uri={thing?.image_url} style={styles.thumb} />
      <View style={styles.lineBody}>
        <Link href={href} asChild>
          <Pressable>
            <ThemedText type="smallBold" numberOfLines={2}>
              {thing?.name ?? 'Item no longer available'}
            </ThemedText>
          </Pressable>
        </Link>
        <ThemedText type="small" themeColor="textSecondary">
          {formatPrice(item.unit_price)} each
        </ThemedText>
        <View style={styles.lineActions}>
          <QuantityStepper
            compact
            value={item.quantity}
            max={max}
            disabled={busy}
            onChange={(quantity) => run(() => update(item.id, quantity))}
          />
          <Button title="Remove" variant="link" onPress={() => run(() => remove(item.id))} disabled={busy} />
        </View>
        {error && (
          <ThemedText type="small" themeColor="danger">
            {error}
          </ThemedText>
        )}
      </View>
      <ThemedText type="smallBold">{formatPrice(item.line_total)}</ThemedText>
    </View>
  );
}

const styles = StyleSheet.create({
  content: {
    padding: Spacing.three,
    paddingBottom: Spacing.six,
    gap: Spacing.four,
    width: '100%',
    maxWidth: MaxContentWidth,
    alignSelf: 'center',
  },
  empty: { gap: Spacing.three, alignItems: 'flex-start' },
  line: {
    flexDirection: 'row',
    gap: Spacing.three,
    paddingVertical: Spacing.three,
    borderBottomWidth: StyleSheet.hairlineWidth,
  },
  thumb: { width: 64, height: 64, borderRadius: Spacing.two },
  lineBody: { flex: 1, gap: Spacing.one },
  lineActions: { flexDirection: 'row', alignItems: 'center', gap: Spacing.two, marginTop: Spacing.one },
});
