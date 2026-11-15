import * as SecureStore from 'expo-secure-store';
import { Platform } from 'react-native';

/**
 * Small key/value store for secrets (auth token, guest cart token).
 * Keychain/Keystore on device; localStorage on web (dev preview only).
 * Values are cached in memory: the API client reads them on every request.
 */
const cache = new Map<string, string | null>();

export const storage = {
  async get(key: string): Promise<string | null> {
    if (cache.has(key)) return cache.get(key) ?? null;
    const value =
      Platform.OS === 'web'
        ? (globalThis.localStorage?.getItem(key) ?? null)
        : await SecureStore.getItemAsync(key);
    cache.set(key, value);
    return value;
  },
  async set(key: string, value: string | null): Promise<void> {
    cache.set(key, value);
    if (Platform.OS === 'web') {
      if (value === null) globalThis.localStorage?.removeItem(key);
      else globalThis.localStorage?.setItem(key, value);
      return;
    }
    if (value === null) await SecureStore.deleteItemAsync(key);
    else await SecureStore.setItemAsync(key, value);
  },
};
