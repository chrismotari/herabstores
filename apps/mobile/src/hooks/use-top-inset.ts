import { Platform } from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';

/** Web preview: native tabs fall back to a bar along the top of the page. */
const WEB_TAB_BAR_HEIGHT = 64;

/**
 * Top padding for tab screens without a header. iOS scroll views inset themselves
 * (`contentInsetAdjustmentBehavior="automatic"`); Android and web need it explicitly.
 */
export function useTopInset(): number {
  const insets = useSafeAreaInsets();
  if (Platform.OS === 'ios') return 0;
  return insets.top + (Platform.OS === 'web' ? WEB_TAB_BAR_HEIGHT : 0);
}
