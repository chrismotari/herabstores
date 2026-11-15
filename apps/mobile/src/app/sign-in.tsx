import { Link, router } from 'expo-router';
import { useRef, useState } from 'react';

import { ThemedText } from '@/components/themed-text';
import { Button } from '@/components/ui/button';
import { FormScreen } from '@/components/ui/form-screen';
import { TextField, type TextFieldRef } from '@/components/ui/text-field';
import { useSession, type FieldErrors } from '@/lib/session';

export default function SignInScreen() {
  const { login } = useSession();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [busy, setBusy] = useState(false);
  const [message, setMessage] = useState<string | null>(null);
  const [errors, setErrors] = useState<FieldErrors>({});
  const passwordRef = useRef<TextFieldRef>(null);

  async function submit() {
    setBusy(true);
    setMessage(null);
    const result = await login(email.trim(), password);
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
      <TextField
        label="Email"
        value={email}
        onChangeText={setEmail}
        autoCapitalize="none"
        autoComplete="email"
        keyboardType="email-address"
        textContentType="username"
        returnKeyType="next"
        onSubmitEditing={() => passwordRef.current?.focus()}
        error={errors.email?.[0]}
      />
      <TextField
        ref={passwordRef}
        label="Password"
        value={password}
        onChangeText={setPassword}
        secureTextEntry
        autoComplete="current-password"
        textContentType="password"
        returnKeyType="go"
        onSubmitEditing={submit}
        error={errors.password?.[0]}
      />
      {message && <ThemedText themeColor="danger">{message}</ThemedText>}
      <Button title="Sign in" onPress={submit} loading={busy} />
      <Link href="/forgot-password" asChild>
        <Button title="Forgot your password?" variant="link" />
      </Link>
      <Link href="/register" replace asChild>
        <Button title="New here? Create an account" variant="outline" />
      </Link>
    </FormScreen>
  );
}
