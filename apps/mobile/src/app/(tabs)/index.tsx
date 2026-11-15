import { useQuery } from '@tanstack/react-query';
import { Image } from 'expo-image';
import { RefreshControl, ScrollView, StyleSheet, View } from 'react-native';

import { ScreenState } from '@/components/screen-state';
import { ThemedText } from '@/components/themed-text';
import { ThemedView } from '@/components/themed-view';
import { MaxContentWidth, Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';
import { useTopInset } from '@/hooks/use-top-inset';
import { api, unwrap } from '@/lib/api';

export default function HomeScreen() {
  const colors = useTheme();
  const topInset = useTopInset();
  const categories = useQuery({
    queryKey: ['categories'],
    queryFn: async () => unwrap(await api.GET('/categories')).data,
  });

  return (
    <ScrollView
      contentInsetAdjustmentBehavior="automatic"
      style={{ backgroundColor: colors.background }}
      contentContainerStyle={[styles.content, { paddingTop: topInset + Spacing.three }]}
      refreshControl={
        <RefreshControl refreshing={categories.isRefetching} onRefresh={() => void categories.refetch()} />
      }>
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

      <ThemedText type="smallBold" style={styles.sectionTitle}>
        Shop by category
      </ThemedText>

      {categories.data ? (
        <View style={styles.grid}>
          {categories.data.map((category) => (
            <ThemedView key={category.id} type="backgroundElement" style={styles.tile}>
              {category.image_url && (
                <Image source={category.image_url} style={styles.tileImage} contentFit="cover" />
              )}
              <ThemedText type="smallBold" numberOfLines={2}>
                {category.name}
              </ThemedText>
            </ThemedView>
          ))}
        </View>
      ) : (
        <ScreenState error={categories.error} onRetry={() => categories.refetch()} />
      )}
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  content: {
    padding: Spacing.three,
    gap: Spacing.three,
    width: '100%',
    maxWidth: MaxContentWidth,
    alignSelf: 'center',
  },
  header: { alignItems: 'center', gap: Spacing.two, paddingVertical: Spacing.three },
  tagline: { textAlign: 'center' },
  logo: { width: 140, height: 72 },
  sectionTitle: { textTransform: 'uppercase', letterSpacing: 1 },
  grid: { flexDirection: 'row', flexWrap: 'wrap', gap: Spacing.three },
  tile: {
    flexBasis: '46%',
    flexGrow: 1,
    borderRadius: Spacing.three,
    padding: Spacing.three,
    gap: Spacing.two,
    minHeight: 72,
    justifyContent: 'flex-end',
  },
  tileImage: { width: '100%', aspectRatio: 1.6, borderRadius: Spacing.two },
});
