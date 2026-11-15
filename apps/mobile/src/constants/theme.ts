/**
 * Herab Stores colours (from the web theme: --color-primary / --color-secondary).
 */
import { Platform } from 'react-native';

export const Brand = {
  primary: '#2d5f2e',
  secondary: '#d6b161',
} as const;

export const Colors = {
  light: {
    text: '#222222',
    background: '#ffffff',
    backgroundElement: '#f4f6f2',
    backgroundSelected: '#e3eadf',
    textSecondary: '#6c757d',
    border: '#e9ecef',
    primary: Brand.primary,
    onPrimary: '#ffffff',
    accent: Brand.secondary,
    danger: '#dc3545',
  },
  dark: {
    text: '#f4f4f2',
    background: '#121411',
    backgroundElement: '#1d211c',
    backgroundSelected: '#2a3128',
    textSecondary: '#b0b4ba',
    border: '#2e332d',
    primary: '#5e9c5f',
    onPrimary: '#ffffff',
    accent: Brand.secondary,
    danger: '#ff6b6b',
  },
} as const;

export type ThemeColor = keyof typeof Colors.light & keyof typeof Colors.dark;

export const Fonts = Platform.select({
  ios: { sans: 'system-ui', serif: 'ui-serif', rounded: 'ui-rounded', mono: 'ui-monospace' },
  default: { sans: 'normal', serif: 'serif', rounded: 'normal', mono: 'monospace' },
  web: { sans: 'system-ui', serif: 'Georgia, serif', rounded: 'system-ui', mono: 'ui-monospace, monospace' },
});

export const Spacing = {
  half: 2,
  one: 4,
  two: 8,
  three: 16,
  four: 24,
  five: 32,
  six: 64,
} as const;

export const MaxContentWidth = 800;
