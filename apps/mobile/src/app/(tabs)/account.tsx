import { Link, type Href } from 'expo-router';
import { useState } from 'react';
import { Alert, Platform, Pressable, ScrollView, StyleSheet, View } from 'react-native';

import { ScreenState } from '@/components/screen-state';
import { ThemedText } from '@/components/themed-text';
import { ThemedView } from '@/components/themed-view';
import { Button } from '@/components/ui/button';
import { MaxContentWidth, Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';
import { useTopInset } from '@/hooks/use-top-inset';
import { api, errorMessage } from '@/lib/api';
import { useSession } from '@/lib/session';

export default function AccountScreen() {
  const colors = useTheme();
  const topInset = useTopInset();
  const { user, loading, logout } = useSession();
  const [verifyStatus, setVerifyStatus] = useState<string | null>(null);

  if (loading) return <ScreenState />;

  async function resendVerification() {
    setVerifyStatus('Sending…');
    const { data, error } = await api.POST('/auth/email/verification-notification');
    setVerifyStatus(data ? data.message : errorMessage(error));
  }

  function confirmLogout() {
    if (Platform.OS === 'web') {
      void logout();
      return;
    }
    Alert.alert('Sign out?', undefined, [
      { text: 'Cancel', style: 'cancel' },
      { text: 'Sign out', style: 'destructive', onPress: () => void logout() },
    ]);
  }

  return (
    <ScrollView
      contentInsetAdjustmentBehavior="automatic"
      style={{ backgroundColor: colors.background }}
      contentContainerStyle={[styles.content, { paddingTop: topInset + Spacing.four }]}>
      {user ? (
        <>
          <View style={styles.header}>
            <ThemedText type="subtitle">Hi, {user.fname}</ThemedText>
            <ThemedText themeColor="textSecondary">{user.email}</ThemedText>
            <ThemedText themeColor="textSecondary">{user.phone}</ThemedText>
          </View>
          {!user.email_verified && (
            <ThemedView type="backgroundElement" style={styles.notice}>
              <ThemedText type="small">
                Please confirm your email address using the link we sent you.
              </ThemedText>
              {verifyStatus ? (
                <ThemedText type="small" themeColor="textSecondary">
                  {verifyStatus}
                </ThemedText>
              ) : (
                <Button title="Resend email" variant="link" onPress={resendVerification} style={styles.resend} />
              )}
            </ThemedView>
          )}
          <View style={[styles.menu, { borderColor: colors.border }]}>
            <MenuRow href="/account/orders" label="My orders" />
            <MenuRow href="/account/favourites" label="Favourites" />
            <MenuRow href="/account/profile" label="Profile and password" last />
          </View>
          <Button title="Sign out" variant="outline" onPress={confirmLogout} />
        </>
      ) : (
        <>
          <View style={styles.header}>
            <ThemedText type="subtitle">Your account</ThemedText>
            <ThemedText themeColor="textSecondary">
              Sign in to track orders, save favourites and check out faster.
            </ThemedText>
          </View>
          <Link href="/sign-in" asChild>
            <Button title="Sign in" />
          </Link>
          <Link href="/register" asChild>
            <Button title="Create an account" variant="outline" />
          </Link>
        </>
      )}
    </ScrollView>
  );
}

function MenuRow({ href, label, last }: { href: Href; label: string; last?: boolean }) {
  const colors = useTheme();
  return (
    <Link href={href} asChild>
      <Pressable
        accessibilityRole="link"
        style={StyleSheet.flatten([styles.menuRow, !last && { borderBottomWidth: StyleSheet.hairlineWidth, borderColor: colors.border }])}>
        <ThemedText>{label}</ThemedText>
        <ThemedText themeColor="textSecondary">›</ThemedText>
      </Pressable>
    </Link>
  );
}

const styles = StyleSheet.create({
  menu: { borderWidth: StyleSheet.hairlineWidth, borderRadius: Spacing.three, overflow: 'hidden' },
  menuRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: Spacing.three,
    minHeight: 52,
  },
  content: {
    padding: Spacing.four,
    gap: Spacing.three,
    width: '100%',
    maxWidth: MaxContentWidth,
    alignSelf: 'center',
  },
  header: { gap: Spacing.one, marginBottom: Spacing.two },
  notice: { padding: Spacing.three, borderRadius: Spacing.two, gap: Spacing.two },
  resend: { alignSelf: 'flex-start', paddingHorizontal: 0, minHeight: 32 },
});
