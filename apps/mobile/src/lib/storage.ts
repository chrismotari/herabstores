import * as SecureStore from 'expo-secure-store';
import { Platform } from 'react-native';

/**
 * Small key/value store for secrets (auth token, guest cart token).
 * Keychain/Keystore on device; localStorage on web (dev preview only).
 */
export const storage = {
  async get(key: string): Promise<string | null> {
    if (Platform.OS === 'web') return globalThis.localStorage?.getItem(key) ?? null;
    return SecureStore.getItemAsync(key);
  },
  async set(key: string, value: string | null): Promise<void> {
    if (Platform.OS === 'web') {
      if (value === null) globalThis.localStorage?.removeItem(key);
      else globalThis.localStorage?.setItem(key, value);
      return;
    }
    if (value === null) await SecureStore.deleteItemAsync(key);
    else await SecureStore.setItemAsync(key, value);
  },
};
