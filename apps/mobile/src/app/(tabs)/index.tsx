import { formatPrice } from '@herabs/shared';
import { useQuery } from '@tanstack/react-query';
import { Image } from 'expo-image';
import { Link, router } from 'expo-router';
import { useState } from 'react';
import { Pressable, RefreshControl, ScrollView, StyleSheet, View } from 'react-native';

import { ProductCard } from '@/components/catalog/product-card';
import { ProductImage } from '@/components/catalog/product-image';
import { SearchBar } from '@/components/catalog/search-bar';
import { ScreenState } from '@/components/screen-state';
import { ThemedText } from '@/components/themed-text';
import { ThemedView } from '@/components/themed-view';
import { MaxContentWidth, Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';
import { useTopInset } from '@/hooks/use-top-inset';
import { basketsQuery, categoriesQuery } from '@/lib/catalog';
import { api, unwrap } from '@/lib/api';

const newestQuery = {
  queryKey: ['products', 'newest'],
  queryFn: async () =>
    unwrap(await api.GET('/products', { params: { query: { sort: 'newest', per_page: 6 } } })).data,
};

export default function HomeScreen() {
  const colors = useTheme();
  const topInset = useTopInset();
  const [q, setQ] = useState('');
  const categories = useQuery(categoriesQuery);
  const baskets = useQuery(basketsQuery);
  const newest = useQuery(newestQuery);

  const refreshing = categories.isRefetching || baskets.isRefetching || newest.isRefetching;
  const refresh = () => {
    void categories.refetch();
    void baskets.refetch();
    void newest.refetch();
  };

  return (
    <ScrollView
      contentInsetAdjustmentBehavior="automatic"
      keyboardShouldPersistTaps="handled"
      style={{ backgroundColor: colors.background }}
      contentContainerStyle={[styles.content, { paddingTop: topInset + Spacing.three }]}
      refreshControl={<RefreshControl refreshing={refreshing} onRefresh={refresh} />}>
      <View style={styles.header}>
        <Image
          source={require('@/assets/images/logo.png')}
          style={styles.logo}
          contentFit="contain"
          accessibilityLabel="Herab Stores"
        />
        <ThemedText themeColor="textSecondary" style={styles.tagline}>
          Fresh groceries delivered in Nairobi. Pay on delivery.
        </ThemedText>
      </View>

      <SearchBar
        value={q}
        onChangeText={setQ}
        onSubmitEditing={() => q.trim() && router.navigate({ pathname: '/shop', params: { q: q.trim() } })}
      />

      <Section title="Shop by category">
        {categories.data ? (
          <View style={styles.grid}>
            {categories.data.map((category) => (
              <Link
                key={category.id}
                href={{ pathname: '/categories/[slug]', params: { slug: category.slug } }}
                asChild>
                <Pressable style={styles.tileWrap}>
                  <ThemedView type="backgroundElement" style={styles.tile}>
                    {category.image_url && <ProductImage uri={category.image_url} style={styles.tileImage} />}
                    <ThemedText type="smallBold" numberOfLines={2}>
                      {category.name}
                    </ThemedText>
                  </ThemedView>
                </Pressable>
              </Link>
            ))}
          </View>
        ) : (
          <ScreenState error={categories.error} onRetry={() => void categories.refetch()} />
        )}
      </Section>

      {!!baskets.data?.length && (
        <Section title="Baskets">
          <ScrollView horizontal showsHorizontalScrollIndicator={false} contentContainerStyle={styles.row}>
            {baskets.data.map((basket) => (
              <Link
                key={basket.id}
                href={{ pathname: '/baskets/[slug]', params: { slug: basket.slug ?? basket.id } }}
                asChild>
                <Pressable style={styles.basket}>
                  <ProductImage uri={basket.image_url} label={basket.name} style={styles.basketImage} />
                  <ThemedText type="smallBold" numberOfLines={1}>
                    {basket.name}
                  </ThemedText>
                  <ThemedText type="small" themeColor="primary">
                    {formatPrice(basket.price)}
                  </ThemedText>
                </Pressable>
              </Link>
            ))}
          </ScrollView>
        </Section>
      )}

      {!!newest.data?.length && (
        <Section title="New in" action={{ label: 'See all', onPress: () => router.navigate('/shop') }}>
          <View style={styles.grid}>
            {newest.data.map((product) => (
              <View key={product.id} style={styles.productCell}>
                <ProductCard product={product} />
              </View>
            ))}
          </View>
        </Section>
      )}
    </ScrollView>
  );
}

function Section({
  title,
  action,
  children,
}: {
  title: string;
  action?: { label: string; onPress: () => void };
  children: React.ReactNode;
}) {
  return (
    <View style={styles.section}>
      <View style={styles.sectionHead}>
        <ThemedText type="smallBold" style={styles.sectionTitle}>
          {title}
        </ThemedText>
        {action && (
          <Pressable onPress={action.onPress} accessibilityRole="button" hitSlop={8}>
            <ThemedText type="smallBold" themeColor="primary">
              {action.label}
            </ThemedText>
          </Pressable>
        )}
      </View>
      {children}
    </View>
  );
}

const styles = StyleSheet.create({
  content: {
    padding: Spacing.three,
    paddingBottom: Spacing.six,
    gap: Spacing.four,
    width: '100%',
    maxWidth: MaxContentWidth,
    alignSelf: 'center',
  },
  header: { alignItems: 'center', gap: Spacing.two },
  tagline: { textAlign: 'center' },
  logo: { width: 140, height: 72 },
  section: { gap: Spacing.three },
  sectionHead: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
  sectionTitle: { textTransform: 'uppercase', letterSpacing: 1 },
  grid: { flexDirection: 'row', flexWrap: 'wrap', gap: Spacing.three },
  tileWrap: { flexBasis: '46%', flexGrow: 1 },
  tile: {
    borderRadius: Spacing.three,
    padding: Spacing.three,
    gap: Spacing.two,
    minHeight: 72,
    justifyContent: 'flex-end',
  },
  tileImage: { width: '100%', aspectRatio: 1.6, borderRadius: Spacing.two },
  row: { gap: Spacing.three },
  basket: { width: 180, gap: Spacing.one },
  basketImage: { width: 180, height: 130, borderRadius: Spacing.three },
  productCell: { flexBasis: '46%', flexGrow: 1, maxWidth: '48%' },
});
