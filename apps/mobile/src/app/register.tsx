import { router } from 'expo-router';
import { useState } from 'react';

import { ThemedText } from '@/components/themed-text';
import { Button } from '@/components/ui/button';
import { FormScreen } from '@/components/ui/form-screen';
import { TextField } from '@/components/ui/text-field';
import { useSession, type FieldErrors, type RegisterInput } from '@/lib/session';

const EMPTY: RegisterInput = { fname: '', lname: '', phone: '', email: '', password: '', password_confirmation: '' };

export default function RegisterScreen() {
  const { register } = useSession();
  const [form, setForm] = useState<RegisterInput>(EMPTY);
  const [busy, setBusy] = useState(false);
  const [message, setMessage] = useState<string | null>(null);
  const [errors, setErrors] = useState<FieldErrors>({});

  const field = (name: keyof RegisterInput) => ({
    value: form[name],
    onChangeText: (value: string) => setForm((f) => ({ ...f, [name]: value })),
    error: errors[name]?.[0],
  });

  async function submit() {
    setBusy(true);
    setMessage(null);
    const result = await register({ ...form, email: form.email.trim() });
    setBusy(false);
    if (result.ok) {
      router.back();
      return;
    }
    setErrors(result.errors);
    if (Object.keys(result.errors).length === 0) setMessage(result.message);
  }

  return (
    <FormScreen>
      <TextField label="First name" autoComplete="given-name" textContentType="givenName" {...field('fname')} />
      <TextField label="Last name" autoComplete="family-name" textContentType="familyName" {...field('lname')} />
      <TextField
        label="Phone"
        keyboardType="phone-pad"
        autoComplete="tel"
        textContentType="telephoneNumber"
        placeholder="07XX XXX XXX"
        {...field('phone')}
      />
      <TextField
        label="Email"
        autoCapitalize="none"
        keyboardType="email-address"
        autoComplete="email"
        textContentType="emailAddress"
        {...field('email')}
      />
      <TextField
        label="Password"
        secureTextEntry
        autoComplete="new-password"
        textContentType="newPassword"
        {...field('password')}
      />
      <TextField
        label="Confirm password"
        secureTextEntry
        autoComplete="new-password"
        textContentType="newPassword"
        {...field('password_confirmation')}
      />
      {message && <ThemedText themeColor="danger">{message}</ThemedText>}
      <Button title="Create account" onPress={submit} loading={busy} />
    </FormScreen>
  );
}
