import { formatPrice } from '@herabs/shared';
import { useQuery } from '@tanstack/react-query';
import { Link, Stack, useLocalSearchParams } from 'expo-router';
import { ScrollView, StyleSheet, useWindowDimensions, View } from 'react-native';

import { AddToCart } from '@/components/cart/add-to-cart';
import { FavouriteButton } from '@/components/catalog/favourite-button';
import { DetailScroll } from '@/components/catalog/detail-scroll';
import { ProductImage } from '@/components/catalog/product-image';
import { StockBadge } from '@/components/catalog/stock-badge';
import { ScreenState } from '@/components/screen-state';
import { ThemedText } from '@/components/themed-text';
import { MaxContentWidth, Spacing } from '@/constants/theme';
import { productQuery } from '@/lib/catalog';

export default function ProductScreen() {
  const { slug } = useLocalSearchParams<{ slug: string }>();
  const product = useQuery(productQuery(slug));
  const { width } = useWindowDimensions();
  const imageWidth = Math.min(width, MaxContentWidth) - Spacing.three * 2;

  if (!product.data) {
    return (
      <>
        <Stack.Screen options={{ title: '' }} />
        <ScreenState error={product.error} onRetry={() => void product.refetch()} />
      </>
    );
  }

  const p = product.data;
  const images = [p.image_url, ...(p.images ?? [])].filter((u, i, all): u is string => !!u && all.indexOf(u) === i);

  return (
    <DetailScroll>
      <Stack.Screen options={{ title: p.category?.name ?? '' }} />
      {images.length > 1 ? (
        <ScrollView horizontal pagingEnabled showsHorizontalScrollIndicator={false} style={{ width: imageWidth }}>
          {images.map((uri) => (
            <ProductImage key={uri} uri={uri} label={p.name} style={[styles.image, { width: imageWidth }]} />
          ))}
        </ScrollView>
      ) : (
        <ProductImage uri={images[0]} label={p.name} style={styles.image} />
      )}

      <View style={styles.info}>
        <ThemedText type="subtitle" style={styles.title}>
          {p.name}
        </ThemedText>
        <ThemedText type="subtitle" themeColor="primary" style={styles.price}>
          {formatPrice(p.price)}
        </ThemedText>
        <StockBadge status={p.stock_status} />
      </View>

      <AddToCart productId={p.id} maxQuantity={p.available_quantity} />
      <FavouriteButton productId={p.id} />

      {!!p.description && <ThemedText themeColor="textSecondary">{p.description}</ThemedText>}

      {p.category && (
        <Link href={{ pathname: '/categories/[slug]', params: { slug: p.category.slug } }}>
          <ThemedText type="smallBold" themeColor="primary">
            More in {p.category.name} ›
          </ThemedText>
        </Link>
      )}
    </DetailScroll>
  );
}

const styles = StyleSheet.create({
  image: { width: '100%', aspectRatio: 1, borderRadius: Spacing.three },
  info: { gap: Spacing.two },
  title: { fontSize: 24, lineHeight: 30 },
  price: { fontSize: 22, lineHeight: 28 },
});
