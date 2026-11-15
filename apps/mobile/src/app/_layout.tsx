import { QueryClientProvider } from '@tanstack/react-query';
import { DarkTheme, DefaultTheme, Stack, ThemeProvider } from 'expo-router';
import { StatusBar } from 'expo-status-bar';
import { useColorScheme } from 'react-native';

import { Colors } from '@/constants/theme';
import { queryClient } from '@/lib/query';
import { SessionProvider } from '@/lib/session';

export default function RootLayout() {
  const scheme = useColorScheme() === 'dark' ? 'dark' : 'light';
  const base = scheme === 'dark' ? DarkTheme : DefaultTheme;
  const navTheme = {
    ...base,
    colors: { ...base.colors, primary: Colors[scheme].primary, background: Colors[scheme].background },
  };

  return (
    <QueryClientProvider client={queryClient}>
      <SessionProvider>
        <ThemeProvider value={navTheme}>
          <Stack screenOptions={{ headerBackButtonDisplayMode: 'minimal' }}>
            <Stack.Screen name="(tabs)" options={{ headerShown: false }} />
            <Stack.Screen name="sign-in" options={{ title: 'Sign in', presentation: 'modal' }} />
            <Stack.Screen name="register" options={{ title: 'Create account', presentation: 'modal' }} />
            <Stack.Screen name="forgot-password" options={{ title: 'Reset password' }} />
            <Stack.Screen name="products/[slug]" options={{ title: '' }} />
            <Stack.Screen name="categories/[slug]" options={{ title: '' }} />
            <Stack.Screen name="baskets/[slug]" options={{ title: 'Basket' }} />
            <Stack.Screen name="checkout/index" options={{ title: 'Checkout' }} />
            <Stack.Screen name="account/orders/index" options={{ title: 'My orders' }} />
            <Stack.Screen name="account/orders/[id]" options={{ title: 'Order' }} />
            <Stack.Screen name="account/favourites" options={{ title: 'Favourites' }} />
            <Stack.Screen name="account/profile" options={{ title: 'Profile' }} />
            <Stack.Screen
              name="checkout/confirmation"
              options={{ title: 'Order placed', headerBackVisible: false, gestureEnabled: false }}
            />
          </Stack>
          <StatusBar style="auto" />
        </ThemeProvider>
      </SessionProvider>
    </QueryClientProvider>
  );
}
