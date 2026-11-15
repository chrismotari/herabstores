import { useQuery } from '@tanstack/react-query';
import { useLocalSearchParams } from 'expo-router';
import { useState } from 'react';
import { View } from 'react-native';

import { Chips } from '@/components/catalog/chips';
import { ProductGrid } from '@/components/catalog/product-grid';
import { SearchBar } from '@/components/catalog/search-bar';
import { Spacing } from '@/constants/theme';
import { useDebounced } from '@/hooks/use-debounced';
import { useTopInset } from '@/hooks/use-top-inset';
import { categoriesQuery, type ProductSort } from '@/lib/catalog';

const SORTS: { value: ProductSort; label: string }[] = [
  { value: 'newest', label: 'Newest' },
  { value: 'price_asc', label: 'Price: low to high' },
  { value: 'price_desc', label: 'Price: high to low' },
  { value: 'name', label: 'Name' },
];

export default function ShopScreen() {
  const params = useLocalSearchParams<{ q?: string; category?: string }>();
  const topInset = useTopInset();
  const categories = useQuery(categoriesQuery);

  // Home links here with ?q= or ?category=; the params seed the filters.
  const [seed, setSeed] = useState(params);
  const [q, setQ] = useState(params.q ?? '');
  const [category, setCategory] = useState(params.category ?? '');
  const [sort, setSort] = useState<ProductSort>('newest');
  if (params.q !== seed.q || params.category !== seed.category) {
    setSeed(params);
    if (params.q !== undefined) setQ(params.q);
    if (params.category !== undefined) setCategory(params.category);
  }
  const search = useDebounced(q.trim());

  const categoryOptions = [
    { value: '', label: 'All' },
    ...(categories.data ?? []).map((c) => ({ value: c.slug, label: c.name })),
  ];

  return (
    <ProductGrid
      topInset={topInset}
      filters={{ q: search, category: category || undefined, sort }}
      header={
        <View style={{ gap: Spacing.three }}>
          <SearchBar value={q} onChangeText={setQ} />
          <Chips label="Category" options={categoryOptions} value={category} onChange={setCategory} />
          <Chips label="Sort" options={SORTS} value={sort} onChange={setSort} />
        </View>
      }
    />
  );
}
