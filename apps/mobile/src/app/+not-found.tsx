import { router, Stack } from 'expo-router';
import { StyleSheet, View } from 'react-native';

import { ThemedText } from '@/components/themed-text';
import { Button } from '@/components/ui/button';
import { Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';

/** Unknown links (e.g. a website page the app doesn't have). */
export default function NotFoundScreen() {
  const colors = useTheme();

  return (
    <View style={[styles.wrap, { backgroundColor: colors.background }]}>
      <Stack.Screen options={{ title: 'Not found' }} />
      <ThemedText type="subtitle" style={styles.center}>
        Page not found
      </ThemedText>
      <ThemedText themeColor="textSecondary" style={styles.center}>
        That page isn&apos;t in the app.
      </ThemedText>
      <Button title="Go to the shop" onPress={() => router.replace('/')} />
    </View>
  );
}

const styles = StyleSheet.create({
  wrap: { flex: 1, justifyContent: 'center', padding: Spacing.four, gap: Spacing.three },
  center: { textAlign: 'center' },
});
