import { router } from 'expo-router';
import { SymbolView } from 'expo-symbols';
import { useState } from 'react';
import { Pressable, StyleSheet } from 'react-native';

import { ThemedText } from '@/components/themed-text';
import { Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';
import { useFavourites } from '@/lib/account';

export function FavouriteButton({ productId }: { productId: string }) {
  const colors = useTheme();
  const { ids, signedIn, toggle } = useFavourites();
  const [busy, setBusy] = useState(false);
  const saved = ids.has(productId);

  async function press() {
    if (!signedIn) {
      router.push('/sign-in');
      return;
    }
    setBusy(true);
    await toggle(productId);
    setBusy(false);
  }

  return (
    <Pressable
      onPress={press}
      disabled={busy}
      accessibilityRole="button"
      accessibilityState={{ selected: saved, busy }}
      style={({ pressed }) => (pressed || busy ? styles.pressed : styles.button)}>
      <SymbolView
        name={{
          ios: saved ? 'heart.fill' : 'heart',
          android: saved ? 'favorite' : 'favorite_border',
          web: saved ? 'favorite' : 'favorite_border',
        }}
        size={20}
        tintColor={colors.primary}
      />
      <ThemedText type="smallBold" themeColor="primary">
        {saved ? 'Saved to favourites' : 'Save to favourites'}
      </ThemedText>
    </Pressable>
  );
}

const styles = StyleSheet.create({
  button: { flexDirection: 'row', alignItems: 'center', gap: Spacing.two, alignSelf: 'flex-start', minHeight: 40 },
  pressed: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: Spacing.two,
    alignSelf: 'flex-start',
    minHeight: 40,
    opacity: 0.6,
  },
});
