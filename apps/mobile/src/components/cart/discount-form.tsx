import type { Cart } from '@herabs/shared';
import { formatPrice } from '@herabs/shared';
import { useState } from 'react';
import { StyleSheet, View } from 'react-native';

import { ThemedText } from '@/components/themed-text';
import { Button } from '@/components/ui/button';
import { TextField } from '@/components/ui/text-field';
import { Spacing } from '@/constants/theme';
import { useCartActions } from '@/lib/cart';

export function DiscountForm({ cart }: { cart: Cart }) {
  const { applyDiscount, removeDiscount } = useCartActions();
  const [code, setCode] = useState('');
  const [busy, setBusy] = useState(false);
  const [error, setError] = useState<string | null>(null);

  async function run(call: () => ReturnType<typeof applyDiscount>) {
    setBusy(true);
    setError(null);
    const result = await call();
    setBusy(false);
    if (result.ok) setCode('');
    else setError(result.message);
  }

  if (cart.discount) {
    const d = cart.discount;
    return (
      <View style={styles.applied}>
        <View style={styles.flex}>
          <ThemedText type="smallBold">Code {d.code}</ThemedText>
          <ThemedText type="small" themeColor={d.error ? 'danger' : 'textSecondary'}>
            {d.error ?? `${d.name ?? 'Discount'}: −${formatPrice(d.amount)}`}
          </ThemedText>
        </View>
        <Button title="Remove" variant="link" onPress={() => run(removeDiscount)} loading={busy} />
      </View>
    );
  }

  return (
    <View style={styles.row}>
      <View style={styles.flex}>
        <TextField
          label="Discount code"
          value={code}
          onChangeText={setCode}
          autoCapitalize="characters"
          autoCorrect={false}
          returnKeyType="done"
          onSubmitEditing={() => code.trim() && run(() => applyDiscount(code.trim()))}
          error={error ?? undefined}
        />
      </View>
      <Button
        title="Apply"
        variant="outline"
        onPress={() => run(() => applyDiscount(code.trim()))}
        disabled={!code.trim()}
        loading={busy}
        style={styles.apply}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  row: { flexDirection: 'row', alignItems: 'flex-start', gap: Spacing.two },
  flex: { flex: 1 },
  apply: { marginTop: 24 },
  applied: { flexDirection: 'row', alignItems: 'center', gap: Spacing.two },
});
