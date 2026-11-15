import { router } from 'expo-router';
import { useState } from 'react';
import { StyleSheet, View } from 'react-native';

import { ThemedText } from '@/components/themed-text';
import { Button } from '@/components/ui/button';
import { Spacing } from '@/constants/theme';
import { useCart, useCartActions } from '@/lib/cart';

import { QuantityStepper } from './quantity-stepper';

type Props = { productId?: string; basketId?: string; maxQuantity: number };

export function AddToCart({ productId, basketId, maxQuantity }: Props) {
  const { add } = useCartActions();
  const { data: cart } = useCart();
  const [quantity, setQuantity] = useState(1);
  const [busy, setBusy] = useState(false);
  const [status, setStatus] = useState<{ ok: boolean; message: string } | null>(null);

  const inCart = cart?.items.find((item) => (productId ? item.product?.id === productId : item.basket?.id === basketId));

  if (maxQuantity < 1) {
    return <Button title="Out of stock" disabled />;
  }

  async function submit() {
    setBusy(true);
    setStatus(null);
    const result = await add({ productId, basketId, quantity });
    setBusy(false);
    setStatus(result.ok ? { ok: true, message: 'Added to your cart.' } : { ok: false, message: result.message });
    if (result.ok) setQuantity(1);
  }

  return (
    <View style={styles.wrap}>
      <View style={styles.row}>
        <QuantityStepper value={quantity} max={maxQuantity} onChange={setQuantity} disabled={busy} />
        <Button title="Add to cart" onPress={submit} loading={busy} style={styles.button} />
      </View>
      {status && (
        <ThemedText type="small" themeColor={status.ok ? 'primary' : 'danger'}>
          {status.message}
        </ThemedText>
      )}
      {inCart && (
        <Button
          title={`${inCart.quantity} in your cart · View cart`}
          variant="link"
          onPress={() => router.navigate('/cart')}
          style={styles.link}
        />
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  wrap: { gap: Spacing.two },
  row: { flexDirection: 'row', alignItems: 'center', gap: Spacing.three },
  button: { flex: 1 },
  link: { alignSelf: 'flex-start', paddingHorizontal: 0 },
});
