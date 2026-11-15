import { useState } from 'react';

import { ThemedText } from '@/components/themed-text';
import { Button } from '@/components/ui/button';
import { FormScreen } from '@/components/ui/form-screen';
import { TextField } from '@/components/ui/text-field';
import { api, errorMessage } from '@/lib/api';

export default function ForgotPasswordScreen() {
  const [email, setEmail] = useState('');
  const [busy, setBusy] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [sent, setSent] = useState<string | null>(null);

  async function submit() {
    setBusy(true);
    setError(null);
    const { data, error: failure } = await api.POST('/auth/forgot-password', { body: { email: email.trim() } });
    setBusy(false);
    if (data) setSent(data.message);
    else setError(errorMessage(failure));
  }

  if (sent) {
    return (
      <FormScreen>
        <ThemedText>{sent}</ThemedText>
        <ThemedText themeColor="textSecondary">
          Open the link in the email to choose a new password, then sign in here.
        </ThemedText>
      </FormScreen>
    );
  }

  return (
    <FormScreen>
      <ThemedText themeColor="textSecondary">We&apos;ll email you a link to reset your password.</ThemedText>
      <TextField
        label="Email"
        value={email}
        onChangeText={setEmail}
        autoCapitalize="none"
        keyboardType="email-address"
        autoComplete="email"
        onSubmitEditing={submit}
        error={error ?? undefined}
      />
      <Button title="Send reset link" onPress={submit} loading={busy} />
    </FormScreen>
  );
}
