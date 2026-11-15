import { useQuery } from '@tanstack/react-query';
import { Stack, useLocalSearchParams } from 'expo-router';

import { ProductGrid } from '@/components/catalog/product-grid';
import { ThemedText } from '@/components/themed-text';
import { categoryQuery } from '@/lib/catalog';

export default function CategoryScreen() {
  const { slug } = useLocalSearchParams<{ slug: string }>();
  const category = useQuery(categoryQuery(slug));

  return (
    <>
      <Stack.Screen options={{ title: category.data?.name ?? '' }} />
      <ProductGrid
        filters={{ category: slug }}
        header={
          category.data?.description && category.data.description !== category.data.name ? (
            <ThemedText themeColor="textSecondary">{category.data.description}</ThemedText>
          ) : undefined
        }
      />
    </>
  );
}
