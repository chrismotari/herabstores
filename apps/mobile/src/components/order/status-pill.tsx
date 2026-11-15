import type { Order } from '@herabs/shared';
import { StyleSheet, View } from 'react-native';

import { ThemedText } from '@/components/themed-text';
import { Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';
import { STATUS_LABEL } from '@/lib/account';

export function StatusPill({ status }: { status: Order['status'] }) {
  const colors = useTheme();
  const color = status === 'canceled' ? colors.danger : status === 'delivered' ? colors.primary : colors.accent;

  return (
    <View style={[styles.pill, { borderColor: color }]}>
      <ThemedText type="small" style={{ color }}>
        {status ? STATUS_LABEL[status] : 'Unknown'}
      </ThemedText>
    </View>
  );
}

const styles = StyleSheet.create({
  pill: { borderWidth: 1, borderRadius: Spacing.five, paddingHorizontal: Spacing.two, alignSelf: 'flex-start' },
});
