import { formatPrice } from '@herabs/shared';
import { StyleSheet, View } from 'react-native';

import { ThemedText } from '@/components/themed-text';
import { Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';

type Line = { label: string; amount: number; negative?: boolean; note?: string };

/** Subtotal / discount / delivery / total rows. */
export function Totals({ lines, total, totalLabel = 'Total' }: { lines: Line[]; total: number; totalLabel?: string }) {
  const colors = useTheme();

  return (
    <View style={styles.wrap}>
      {lines.map((line) => (
        <View key={line.label} style={styles.row}>
          <ThemedText themeColor="textSecondary">{line.label}</ThemedText>
          <ThemedText themeColor={line.negative ? 'primary' : 'text'}>
            {line.note ?? `${line.negative ? '−' : ''}${formatPrice(line.amount)}`}
          </ThemedText>
        </View>
      ))}
      <View style={[styles.row, styles.total, { borderColor: colors.border }]}>
        <ThemedText type="smallBold">{totalLabel}</ThemedText>
        <ThemedText type="smallBold" style={styles.totalAmount}>
          {formatPrice(total)}
        </ThemedText>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  wrap: { gap: Spacing.two },
  row: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
  total: { borderTopWidth: StyleSheet.hairlineWidth, paddingTop: Spacing.two },
  totalAmount: { fontSize: 18 },
});
