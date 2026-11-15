import Constants from 'expo-constants';

/**
 * Base URL of the Herab Stores API (`/api/v1`).
 *
 * Set `EXPO_PUBLIC_API_URL` in `.env.local`. In development, if it's unset, we use the
 * machine running the Expo dev server on port 8000, so a phone on the same Wi-Fi
 * (or an emulator) reaches `php artisan serve --host 0.0.0.0` without extra setup.
 */
function devApiUrl(): string {
  const host = Constants.expoConfig?.hostUri?.split(':')[0];
  return `http://${host ?? 'localhost'}:8000/api/v1`;
}

export const API_URL = (process.env.EXPO_PUBLIC_API_URL || (__DEV__ ? devApiUrl() : '')).replace(
  /\/$/,
  '',
);
