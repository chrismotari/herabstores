import { Link } from 'expo-router';
import { StyleSheet, View } from 'react-native';

import { ThemedText } from '@/components/themed-text';
import { Button } from '@/components/ui/button';
import { Spacing } from '@/constants/theme';

export function SignInPrompt({ message = 'Please sign in to continue.' }: { message?: string }) {
  return (
    <View style={styles.wrap}>
      <ThemedText themeColor="textSecondary" style={styles.text}>
        {message}
      </ThemedText>
      <Link href="/sign-in" asChild>
        <Button title="Sign in" />
      </Link>
    </View>
  );
}

const styles = StyleSheet.create({
  wrap: { flex: 1, justifyContent: 'center', padding: Spacing.four, gap: Spacing.three },
  text: { textAlign: 'center' },
});
