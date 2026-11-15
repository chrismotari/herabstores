import { useQuery } from '@tanstack/react-query';
import { FlatList, RefreshControl, StyleSheet, View } from 'react-native';

import { ProductCard } from '@/components/catalog/product-card';
import { ScreenState } from '@/components/screen-state';
import { SignInPrompt } from '@/components/sign-in-prompt';
import { ThemedText } from '@/components/themed-text';
import { MaxContentWidth, Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';
import { favouritesQuery } from '@/lib/account';
import { useSession } from '@/lib/session';

export default function FavouritesScreen() {
  const { user } = useSession();
  if (!user) return <SignInPrompt message="Sign in to see your favourites." />;
  return <Favourites />;
}

function Favourites() {
  const colors = useTheme();
  const query = useQuery(favouritesQuery);
  const products = [...(query.data ?? [])];
  const cells = products.length % 2 ? [...products, null] : products;

  return (
    <FlatList
      data={cells}
      numColumns={2}
      keyExtractor={(p) => p?.id ?? 'filler'}
      renderItem={({ item }) => (item ? <ProductCard product={item} /> : <View style={styles.filler} />)}
      contentInsetAdjustmentBehavior="automatic"
      style={{ backgroundColor: colors.background }}
      contentContainerStyle={styles.content}
      columnWrapperStyle={styles.row}
      refreshControl={<RefreshControl refreshing={query.isRefetching} onRefresh={() => void query.refetch()} />}
      ListEmptyComponent={
        query.isPending || query.isError ? (
          <ScreenState error={query.error} onRetry={() => void query.refetch()} />
        ) : (
          <ThemedText themeColor="textSecondary">
            Nothing saved yet. Tap &quot;Save to favourites&quot; on a product to keep it here.
          </ThemedText>
        )
      }
    />
  );
}

const styles = StyleSheet.create({
  content: { padding: Spacing.three, gap: Spacing.four, width: '100%', maxWidth: MaxContentWidth, alignSelf: 'center' },
  row: { gap: Spacing.three },
  filler: { flex: 1, flexBasis: 0 },
});
