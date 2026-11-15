import type { Product } from '@herabs/shared';
import { StyleSheet, View } from 'react-native';

import { ThemedText } from '@/components/themed-text';
import { Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';

export function StockBadge({ status }: { status: Product['stock_status'] }) {
  const colors = useTheme();
  const color = status === 'Out of Stock' ? colors.danger : status === 'Few Items Left' ? colors.accent : colors.primary;

  return (
    <View style={[styles.badge, { borderColor: color }]}>
      <ThemedText type="small" style={[styles.text, { color }]}>
        {status}
      </ThemedText>
    </View>
  );
}

const styles = StyleSheet.create({
  badge: { alignSelf: 'flex-start', borderWidth: 1, borderRadius: Spacing.one, paddingHorizontal: Spacing.two },
  text: { fontSize: 12, lineHeight: 18 },
});
