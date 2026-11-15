import { StyleSheet } from 'react-native';

import { ThemedText } from '@/components/themed-text';
import { ThemedView } from '@/components/themed-view';
import { Spacing } from '@/constants/theme';

export default function CartScreen() {
  return (
    <ThemedView style={styles.container}>
      <ThemedText type="subtitle">Cart</ThemedText>
      <ThemedText themeColor="textSecondary">Your cart and checkout (task 6.4).</ThemedText>
    </ThemedView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, alignItems: 'center', justifyContent: 'center', gap: Spacing.two, padding: Spacing.four },
});
