import { Pressable, StyleSheet, View } from 'react-native';

import { ThemedText } from '@/components/themed-text';
import { Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';

type Props = {
  value: number;
  min?: number;
  max: number;
  onChange: (value: number) => void;
  disabled?: boolean;
  compact?: boolean;
};

export function QuantityStepper({ value, min = 1, max, onChange, disabled, compact }: Props) {
  const colors = useTheme();
  const size = compact ? 34 : 44;

  const step = (delta: number, label: string, symbol: string) => {
    const next = value + delta;
    const blocked = disabled || next < min || next > max;
    return (
      <Pressable
        accessibilityRole="button"
        accessibilityLabel={label}
        accessibilityState={{ disabled: blocked }}
        disabled={blocked}
        hitSlop={6}
        onPress={() => onChange(next)}
        style={StyleSheet.flatten([styles.button, { width: size, height: size, opacity: blocked ? 0.35 : 1 }])}>
        <ThemedText type="subtitle" themeColor="primary" style={styles.symbol}>
          {symbol}
        </ThemedText>
      </Pressable>
    );
  };

  return (
    <View style={[styles.row, { borderColor: colors.border }]}>
      {step(-1, 'Decrease quantity', '−')}
      <ThemedText type="smallBold" style={styles.value} accessibilityLabel={`Quantity ${value}`}>
        {value}
      </ThemedText>
      {step(1, 'Increase quantity', '+')}
    </View>
  );
}

const styles = StyleSheet.create({
  row: { flexDirection: 'row', alignItems: 'center', borderWidth: 1, borderRadius: Spacing.five, alignSelf: 'flex-start' },
  button: { alignItems: 'center', justifyContent: 'center' },
  symbol: { fontSize: 22, lineHeight: 26 },
  value: { minWidth: 28, textAlign: 'center' },
});
