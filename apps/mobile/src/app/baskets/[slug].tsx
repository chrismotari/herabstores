import { formatPrice } from '@herabs/shared';
import { useQuery } from '@tanstack/react-query';
import { Link, Stack, useLocalSearchParams } from 'expo-router';
import { Pressable, StyleSheet, View } from 'react-native';

import { DetailScroll } from '@/components/catalog/detail-scroll';
import { ProductImage } from '@/components/catalog/product-image';
import { ScreenState } from '@/components/screen-state';
import { ThemedText } from '@/components/themed-text';
import { Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';
import { basketQuery } from '@/lib/catalog';
import { formatDate } from '@/lib/format';

export default function BasketScreen() {
  const { slug } = useLocalSearchParams<{ slug: string }>();
  const basket = useQuery(basketQuery(slug));
  const colors = useTheme();

  if (!basket.data) {
    return (
      <>
        <Stack.Screen options={{ title: 'Basket' }} />
        <ScreenState error={basket.error} onRetry={() => void basket.refetch()} />
      </>
    );
  }

  const b = basket.data;

  return (
    <DetailScroll>
      <Stack.Screen options={{ title: 'Basket' }} />
      <ProductImage uri={b.image_url} label={b.name} style={styles.image} />
      <View style={styles.info}>
        <ThemedText type="subtitle" style={styles.title}>
          {b.name}
        </ThemedText>
        <ThemedText type="subtitle" themeColor="primary" style={styles.price}>
          {formatPrice(b.price)}
        </ThemedText>
        <ThemedText type="small" themeColor="textSecondary">
          Available until {formatDate(b.available_until)}
          {!b.in_stock && ' · Sold out'}
        </ThemedText>
      </View>

      {/* Add to cart: task 6.4 */}

      {!!b.description && <ThemedText themeColor="textSecondary">{b.description}</ThemedText>}

      {!!b.items?.length && (
        <View style={styles.items}>
          <ThemedText type="smallBold">What&apos;s inside</ThemedText>
          {b.items.map(({ quantity, product }) => (
            <Link key={product.id} href={{ pathname: '/products/[slug]', params: { slug: product.slug } }} asChild>
              <Pressable style={StyleSheet.flatten([styles.item, { borderColor: colors.border }])}>
                <ProductImage uri={product.image_url} style={styles.thumb} />
                <ThemedText style={styles.itemName} numberOfLines={2}>
                  {product.name}
                </ThemedText>
                <ThemedText type="smallBold">× {quantity}</ThemedText>
              </Pressable>
            </Link>
          ))}
        </View>
      )}
    </DetailScroll>
  );
}

const styles = StyleSheet.create({
  image: { width: '100%', aspectRatio: 1.4, borderRadius: Spacing.three },
  info: { gap: Spacing.two },
  title: { fontSize: 24, lineHeight: 30 },
  price: { fontSize: 22, lineHeight: 28 },
  items: { gap: Spacing.two },
  item: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: Spacing.three,
    paddingVertical: Spacing.two,
    borderBottomWidth: StyleSheet.hairlineWidth,
  },
  thumb: { width: 48, height: 48, borderRadius: Spacing.two },
  itemName: { flex: 1 },
});
