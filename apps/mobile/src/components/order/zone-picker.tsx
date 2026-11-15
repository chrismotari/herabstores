import { formatPrice, type DeliveryZone } from '@herabs/shared';
import { Pressable, StyleSheet, View } from 'react-native';

import { ThemedText } from '@/components/themed-text';
import { Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';

type Props = { zones: DeliveryZone[]; value: string; onChange: (id: string) => void; error?: string };

/** Delivery areas with their fees, as a radio list. */
export function ZonePicker({ zones, value, onChange, error }: Props) {
  const colors = useTheme();

  return (
    <View style={styles.list} accessibilityRole="radiogroup">
      {zones.map((zone) => {
        const selected = zone.id === value;
        return (
          <Pressable
            key={zone.id}
            accessibilityRole="radio"
            accessibilityState={{ checked: selected }}
            onPress={() => onChange(zone.id)}
            style={StyleSheet.flatten([
              styles.zone,
              { borderColor: selected ? colors.primary : colors.border },
              selected && { backgroundColor: colors.backgroundSelected },
            ])}>
            <View style={styles.flex}>
              <ThemedText type="smallBold">{zone.name}</ThemedText>
              {!!zone.description && (
                <ThemedText type="small" themeColor="textSecondary">
                  {zone.description}
                </ThemedText>
              )}
            </View>
            <ThemedText type="smallBold">{formatPrice(zone.delivery_fee)}</ThemedText>
          </Pressable>
        );
      })}
      {error && (
        <ThemedText type="small" themeColor="danger">
          {error}
        </ThemedText>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  list: { gap: Spacing.two },
  zone: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: Spacing.three,
    borderWidth: 1,
    borderRadius: Spacing.two,
    padding: Spacing.three,
  },
  flex: { flex: 1 },
});
