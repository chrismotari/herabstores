import type { Product } from '@herabs/shared';
import { useInfiniteQuery } from '@tanstack/react-query';
import type { ReactElement } from 'react';
import { ActivityIndicator, FlatList, RefreshControl, StyleSheet, View } from 'react-native';

import { ScreenState } from '@/components/screen-state';
import { ThemedText } from '@/components/themed-text';
import { MaxContentWidth, Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';
import { productsQuery, type ProductFilters } from '@/lib/catalog';

import { ProductCard } from './product-card';

type Props = {
  filters: ProductFilters;
  header?: ReactElement;
  topInset?: number;
};

/** Two-column, infinitely scrolling product list. */
export function ProductGrid({ filters, header, topInset = 0 }: Props) {
  const colors = useTheme();
  const query = useInfiniteQuery(productsQuery(filters));
  const products: (Product | null)[] = query.data?.pages.flatMap((page) => page.data) ?? [];
  // An empty cell keeps a lone last card at half width.
  if (products.length % 2 === 1) products.push(null);
  const total = query.data?.pages[0]?.meta.total;

  return (
    <FlatList
      data={products}
      keyExtractor={(product) => product?.id ?? 'filler'}
      numColumns={2}
      renderItem={({ item }) => (item ? <ProductCard product={item} /> : <View style={styles.filler} />)}
      ListFooterComponent={
        query.isFetchingNextPage ? <ActivityIndicator color={colors.primary} style={styles.footer} /> : undefined
      }
      ListHeaderComponent={
        <View style={styles.header}>
          {header}
          {total !== undefined && (
            <ThemedText type="small" themeColor="textSecondary">
              {total === 1 ? '1 product' : `${total} products`}
            </ThemedText>
          )}
        </View>
      }
      ListEmptyComponent={
        query.isPending || query.isError ? (
          <ScreenState error={query.error} onRetry={() => void query.refetch()} />
        ) : (
          <ThemedText themeColor="textSecondary" style={styles.empty}>
            No products found.
          </ThemedText>
        )
      }
      onEndReached={() => query.hasNextPage && !query.isFetchingNextPage && void query.fetchNextPage()}
      onEndReachedThreshold={0.5}
      refreshControl={<RefreshControl refreshing={query.isRefetching && !query.isFetchingNextPage} onRefresh={() => void query.refetch()} />}
      contentInsetAdjustmentBehavior="automatic"
      keyboardDismissMode="on-drag"
      style={{ backgroundColor: colors.background }}
      contentContainerStyle={[styles.content, { paddingTop: topInset + Spacing.three }]}
      columnWrapperStyle={styles.row}
    />
  );
}

const styles = StyleSheet.create({
  content: { padding: Spacing.three, gap: Spacing.four, width: '100%', maxWidth: MaxContentWidth, alignSelf: 'center' },
  row: { gap: Spacing.three },
  header: { gap: Spacing.three },
  footer: { margin: Spacing.three },
  filler: { flex: 1, flexBasis: 0 },
  empty: { textAlign: 'center', marginTop: Spacing.five },
});
