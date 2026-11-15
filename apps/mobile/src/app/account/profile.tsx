import { useQuery } from '@tanstack/react-query';
import { router } from 'expo-router';
import { useState } from 'react';
import { Alert, Platform, StyleSheet, View } from 'react-native';

import { ZonePicker } from '@/components/order/zone-picker';
import { SignInPrompt } from '@/components/sign-in-prompt';
import { ThemedText } from '@/components/themed-text';
import { Button } from '@/components/ui/button';
import { FormScreen } from '@/components/ui/form-screen';
import { TextField } from '@/components/ui/text-field';
import { Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';
import { api, errorMessage } from '@/lib/api';
import { zonesQuery } from '@/lib/catalog';
import { useSession, type FieldErrors } from '@/lib/session';

type Status = { ok: boolean; message: string } | null;
const fieldErrors = (error: unknown): FieldErrors => (error as { errors?: FieldErrors } | undefined)?.errors ?? {};

export default function ProfileScreen() {
  const { user } = useSession();
  if (!user) return <SignInPrompt message="Sign in to edit your profile." />;
  return (
    <FormScreen>
      <Details />
      <Divider />
      <ChangePassword />
      <Divider />
      <DeleteAccount />
    </FormScreen>
  );
}

function Divider() {
  const colors = useTheme();
  return <View style={[styles.divider, { backgroundColor: colors.border }]} />;
}

function Details() {
  const { user, setUser } = useSession();
  const zones = useQuery(zonesQuery);
  const [form, setForm] = useState({
    fname: user?.fname ?? '',
    lname: user?.lname ?? '',
    phone: user?.phone ?? '',
    email: user?.email ?? '',
    address: user?.address ?? '',
    city: user?.city ?? '',
    delivery_zone_id: user?.delivery_zone_id ?? '',
  });
  const [errors, setErrors] = useState<FieldErrors>({});
  const [status, setStatus] = useState<Status>(null);
  const [busy, setBusy] = useState(false);

  const field = (name: Exclude<keyof typeof form, 'delivery_zone_id'>) => ({
    value: form[name],
    onChangeText: (value: string) => setForm((f) => ({ ...f, [name]: value })),
    error: errors[name]?.[0],
  });

  async function save() {
    setBusy(true);
    setStatus(null);
    setErrors({});
    const { data, error } = await api.PATCH('/me', {
      body: {
        ...form,
        email: form.email.trim(),
        address: form.address.trim() || null,
        city: form.city.trim() || null,
        delivery_zone_id: form.delivery_zone_id || null,
      },
    });
    setBusy(false);
    if (data) {
      setUser(data.data);
      setStatus({ ok: true, message: 'Saved.' });
    } else {
      setErrors(fieldErrors(error));
      setStatus({ ok: false, message: errorMessage(error) });
    }
  }

  return (
    <View style={styles.section}>
      <ThemedText type="smallBold" style={styles.heading}>
        Your details
      </ThemedText>
      <TextField label="First name" autoComplete="given-name" {...field('fname')} />
      <TextField label="Last name" autoComplete="family-name" {...field('lname')} />
      <TextField label="Phone" keyboardType="phone-pad" autoComplete="tel" {...field('phone')} />
      <TextField label="Email" autoCapitalize="none" keyboardType="email-address" {...field('email')} />
      <ThemedText type="small" themeColor="textSecondary">
        Changing your email means confirming the new address.
      </ThemedText>

      <ThemedText type="smallBold" style={styles.heading}>
        Default delivery address
      </ThemedText>
      <TextField label="Address" multiline placeholder="Estate, street, building, house number" {...field('address')} />
      <TextField label="City / town" {...field('city')} />
      {zones.data && (
        <ZonePicker
          zones={zones.data}
          value={form.delivery_zone_id}
          onChange={(id) => setForm((f) => ({ ...f, delivery_zone_id: id }))}
          error={errors.delivery_zone_id?.[0]}
        />
      )}
      {status && <ThemedText themeColor={status.ok ? 'primary' : 'danger'}>{status.message}</ThemedText>}
      <Button title="Save details" onPress={save} loading={busy} />
    </View>
  );
}

function ChangePassword() {
  const [form, setForm] = useState({ current_password: '', password: '', password_confirmation: '' });
  const [errors, setErrors] = useState<FieldErrors>({});
  const [status, setStatus] = useState<Status>(null);
  const [busy, setBusy] = useState(false);

  const field = (name: keyof typeof form) => ({
    value: form[name],
    onChangeText: (value: string) => setForm((f) => ({ ...f, [name]: value })),
    error: errors[name]?.[0],
    secureTextEntry: true,
  });

  async function save() {
    setBusy(true);
    setStatus(null);
    setErrors({});
    const { data, error } = await api.PUT('/me/password', { body: form });
    setBusy(false);
    if (data) {
      setForm({ current_password: '', password: '', password_confirmation: '' });
      setStatus({ ok: true, message: 'Password changed. Other devices have been signed out.' });
    } else {
      setErrors(fieldErrors(error));
      setStatus({ ok: false, message: errorMessage(error) });
    }
  }

  return (
    <View style={styles.section}>
      <ThemedText type="smallBold" style={styles.heading}>
        Change password
      </ThemedText>
      <TextField label="Current password" autoComplete="current-password" {...field('current_password')} />
      <TextField label="New password" autoComplete="new-password" {...field('password')} />
      <TextField label="Confirm new password" autoComplete="new-password" {...field('password_confirmation')} />
      {status && <ThemedText themeColor={status.ok ? 'primary' : 'danger'}>{status.message}</ThemedText>}
      <Button title="Change password" variant="outline" onPress={save} loading={busy} />
    </View>
  );
}

function DeleteAccount() {
  const { forget } = useSession();
  const [password, setPassword] = useState('');
  const [error, setError] = useState<string | null>(null);
  const [busy, setBusy] = useState(false);

  async function destroy() {
    setBusy(true);
    setError(null);
    // The password goes in the body, never the URL (see AccountController::destroy).
    const { data, error: failure } = await api.DELETE('/me', { body: { password } } as never);
    setBusy(false);
    if (data) {
      await forget();
      router.dismissTo('/');
    } else {
      setError(errorMessage(failure));
    }
  }

  function confirm() {
    const message = 'This deletes your account. Past orders are kept for our records. This cannot be undone.';
    if (Platform.OS === 'web') {
      if (globalThis.confirm?.(message)) void destroy();
      return;
    }
    Alert.alert('Delete account?', message, [
      { text: 'Cancel', style: 'cancel' },
      { text: 'Delete', style: 'destructive', onPress: () => void destroy() },
    ]);
  }

  return (
    <View style={styles.section}>
      <ThemedText type="smallBold" style={styles.heading}>
        Delete account
      </ThemedText>
      <TextField
        label="Password"
        secureTextEntry
        autoComplete="current-password"
        value={password}
        onChangeText={setPassword}
        error={error ?? undefined}
      />
      <Button title="Delete my account" variant="outline" onPress={confirm} disabled={!password} loading={busy} />
    </View>
  );
}

const styles = StyleSheet.create({
  section: { gap: Spacing.three },
  heading: { textTransform: 'uppercase', letterSpacing: 1, marginTop: Spacing.two },
  divider: { height: StyleSheet.hairlineWidth, marginVertical: Spacing.two },
});
