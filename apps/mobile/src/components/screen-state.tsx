import { ActivityIndicator, Pressable, StyleSheet, View } from 'react-native';

import { ThemedText } from '@/components/themed-text';
import { Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';
import { ApiError } from '@/lib/api';

/** Loading spinner, or an error message with a retry button. */
export function ScreenState({ error, onRetry }: { error?: Error | null; onRetry?: () => void }) {
  const colors = useTheme();

  if (!error) {
    return (
      <View style={styles.center}>
        <ActivityIndicator color={colors.primary} />
      </View>
    );
  }

  const notFound = error instanceof ApiError && error.status === 404;

  return (
    <View style={styles.center}>
      <ThemedText themeColor="textSecondary" style={styles.message}>
        {notFound ? "Sorry, we couldn't find that. It may no longer be available." : error.message}
      </ThemedText>
      {onRetry && !notFound && (
        <Pressable
          onPress={onRetry}
          style={({ pressed }) => [styles.button, { backgroundColor: colors.primary }, pressed && { opacity: 0.8 }]}>
          <ThemedText themeColor="onPrimary" type="smallBold">
            Try again
          </ThemedText>
        </Pressable>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  center: { flex: 1, alignItems: 'center', justifyContent: 'center', padding: Spacing.four, gap: Spacing.three },
  message: { textAlign: 'center' },
  button: { paddingVertical: Spacing.two, paddingHorizontal: Spacing.four, borderRadius: Spacing.two },
});
