import { formatPrice, type Product } from '@herabs/shared';
import { Link } from 'expo-router';
import { Pressable, StyleSheet, View } from 'react-native';

import { ThemedText } from '@/components/themed-text';
import { Spacing } from '@/constants/theme';

import { ProductImage } from './product-image';
import { StockBadge } from './stock-badge';

export function ProductCard({ product }: { product: Product }) {
  return (
    // Size the cell here: `Link asChild` only passes flat styles through.
    <View style={styles.cell}>
      <Link href={{ pathname: '/products/[slug]', params: { slug: product.slug } }} asChild>
        <Pressable
          style={({ pressed }) => (pressed ? styles.cardPressed : styles.card)}
          accessibilityLabel={product.name}>
          <ProductImage uri={product.image_url} style={styles.image} />
          <View style={styles.body}>
            <ThemedText type="small" numberOfLines={2} style={styles.name}>
              {product.name}
            </ThemedText>
            <ThemedText type="smallBold" themeColor="primary">
              {formatPrice(product.price)}
            </ThemedText>
            {!product.in_stock && <StockBadge status={product.stock_status} />}
          </View>
        </Pressable>
      </Link>
    </View>
  );
}

const styles = StyleSheet.create({
  cell: { flex: 1, flexBasis: 0, minWidth: 0 },
  card: { gap: Spacing.two },
  cardPressed: { gap: Spacing.two, opacity: 0.75 },
  image: { width: '100%', aspectRatio: 1, borderRadius: Spacing.three },
  body: { gap: Spacing.one },
  name: { minHeight: 40 },
});
