import { Image, type ImageStyle } from 'expo-image';
import type { StyleProp } from 'react-native';

import { useTheme } from '@/hooks/use-theme';

const PLACEHOLDER = require('@/assets/images/logo.png');

/** Product/basket/category image, with the logo when there's no photo. */
export function ProductImage({ uri, style, label }: { uri: string | null | undefined; style?: StyleProp<ImageStyle>; label?: string }) {
  const colors = useTheme();

  return (
    <Image
      source={uri ? { uri } : PLACEHOLDER}
      placeholder={PLACEHOLDER}
      placeholderContentFit="contain"
      contentFit={uri ? 'cover' : 'contain'}
      transition={150}
      accessibilityLabel={label}
      style={[{ backgroundColor: colors.backgroundElement }, style]}
    />
  );
}
