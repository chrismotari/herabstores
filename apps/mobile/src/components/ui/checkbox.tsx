import { Pressable, StyleSheet, View } from 'react-native';

import { ThemedText } from '@/components/themed-text';
import { Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';

type Props = { checked: boolean; onChange: (checked: boolean) => void; children: React.ReactNode; error?: string };

export function Checkbox({ checked, onChange, children, error }: Props) {
  const colors = useTheme();

  return (
    <View style={styles.wrap}>
      <Pressable
        accessibilityRole="checkbox"
        accessibilityState={{ checked }}
        onPress={() => onChange(!checked)}
        style={styles.row}
        hitSlop={4}>
        <View
          style={StyleSheet.flatten([
            styles.box,
            { borderColor: error ? colors.danger : colors.primary },
            checked && { backgroundColor: colors.primary },
          ])}>
          {checked && (
            <ThemedText type="smallBold" style={{ color: colors.onPrimary }}>
              ✓
            </ThemedText>
          )}
        </View>
        <View style={styles.label}>{children}</View>
      </Pressable>
      {error && (
        <ThemedText type="small" themeColor="danger">
          {error}
        </ThemedText>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  wrap: { gap: Spacing.one },
  row: { flexDirection: 'row', alignItems: 'center', gap: Spacing.three, minHeight: 40 },
  box: { width: 24, height: 24, borderWidth: 2, borderRadius: 6, alignItems: 'center', justifyContent: 'center' },
  label: { flex: 1 },
});
