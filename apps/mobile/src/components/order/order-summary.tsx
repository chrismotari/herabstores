import { formatPrice, type Order } from '@herabs/shared';
import { StyleSheet, View } from 'react-native';

import { Totals } from '@/components/cart/totals';
import { ThemedText } from '@/components/themed-text';
import { Spacing } from '@/constants/theme';

/** Items, totals and delivery details of a placed order. */
export function OrderSummary({ order }: { order: Order }) {
  const d = order.delivery;

  return (
    <View style={styles.wrap}>
      <View style={styles.block}>
        {order.items.map((item, i) => (
          <View key={`${item.product_id ?? item.basket_id}-${i}`} style={styles.line}>
            <ThemedText style={styles.flex}>
              {item.quantity} × {item.name}
            </ThemedText>
            <ThemedText>{formatPrice(item.line_total)}</ThemedText>
          </View>
        ))}
      </View>
      <Totals
        lines={[
          { label: 'Subtotal', amount: order.subtotal },
          ...(order.discount > 0
            ? [{ label: `Discount${order.discount_code ? ` (${order.discount_code})` : ''}`, amount: order.discount, negative: true }]
            : []),
          { label: `Delivery${d.zone ? ` (${d.zone.name})` : ''}`, amount: order.delivery_fee },
        ]}
        total={order.total}
        totalLabel="Pay on delivery"
      />
      <View style={styles.block}>
        <ThemedText type="smallBold">Delivering to</ThemedText>
        <ThemedText themeColor="textSecondary">
          {[d.fname, d.lname].filter(Boolean).join(' ')}
          {'\n'}
          {d.phone}
          {d.alt_phone ? ` / ${d.alt_phone}` : ''}
          {'\n'}
          {[d.address, d.city].filter(Boolean).join(', ')}
        </ThemedText>
        {!!d.message && <ThemedText themeColor="textSecondary">Note: {d.message}</ThemedText>}
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  wrap: { gap: Spacing.four },
  block: { gap: Spacing.two },
  line: { flexDirection: 'row', gap: Spacing.three },
  flex: { flex: 1 },
});
