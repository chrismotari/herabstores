import { StyleSheet } from 'react-native';

import { ThemedText } from '@/components/themed-text';
import { ThemedView } from '@/components/themed-view';
import { Spacing } from '@/constants/theme';

export default function ShopScreen() {
  return (
    <ThemedView style={styles.container}>
      <ThemedText type="subtitle">Shop</ThemedText>
      <ThemedText themeColor="textSecondary">Browse products and search (task 6.3).</ThemedText>
    </ThemedView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, alignItems: 'center', justifyContent: 'center', gap: Spacing.two, padding: Spacing.four },
});
