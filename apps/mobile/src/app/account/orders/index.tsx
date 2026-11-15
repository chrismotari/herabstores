import { formatPrice } from '@herabs/shared';
import { useInfiniteQuery } from '@tanstack/react-query';
import { Link } from 'expo-router';
import { ActivityIndicator, FlatList, Pressable, RefreshControl, StyleSheet, View } from 'react-native';

import { StatusPill } from '@/components/order/status-pill';
import { ScreenState } from '@/components/screen-state';
import { SignInPrompt } from '@/components/sign-in-prompt';
import { ThemedText } from '@/components/themed-text';
import { MaxContentWidth, Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';
import { ordersQuery } from '@/lib/account';
import { formatDate } from '@/lib/format';
import { useSession } from '@/lib/session';

export default function OrdersScreen() {
  const { user } = useSession();
  if (!user) return <SignInPrompt message="Sign in to see your orders." />;
  return <Orders />;
}

function Orders() {
  const colors = useTheme();
  const query = useInfiniteQuery(ordersQuery);
  const orders = query.data?.pages.flatMap((page) => page.data) ?? [];

  return (
    <FlatList
      data={orders}
      keyExtractor={(order) => order.id}
      contentInsetAdjustmentBehavior="automatic"
      style={{ backgroundColor: colors.background }}
      contentContainerStyle={styles.content}
      refreshControl={<RefreshControl refreshing={query.isRefetching} onRefresh={() => void query.refetch()} />}
      onEndReached={() => query.hasNextPage && !query.isFetchingNextPage && void query.fetchNextPage()}
      ListFooterComponent={query.isFetchingNextPage ? <ActivityIndicator color={colors.primary} /> : undefined}
      ListEmptyComponent={
        query.isPending || query.isError ? (
          <ScreenState error={query.error} onRetry={() => void query.refetch()} />
        ) : (
          <ThemedText themeColor="textSecondary">You haven&apos;t placed any orders yet.</ThemedText>
        )
      }
      renderItem={({ item: order }) => (
        <Link href={{ pathname: '/account/orders/[id]', params: { id: order.id } }} asChild>
          <Pressable style={StyleSheet.flatten([styles.row, { borderColor: colors.border }])}>
            <View style={styles.flex}>
              <ThemedText type="smallBold">Order #{order.order_number}</ThemedText>
              <ThemedText type="small" themeColor="textSecondary">
                {formatDate(order.created_at)} · {order.items.length} {order.items.length === 1 ? 'item' : 'items'}
              </ThemedText>
            </View>
            <View style={styles.right}>
              <ThemedText type="smallBold">{formatPrice(order.total)}</ThemedText>
              <StatusPill status={order.status} />
            </View>
          </Pressable>
        </Link>
      )}
    />
  );
}

const styles = StyleSheet.create({
  content: { padding: Spacing.three, width: '100%', maxWidth: MaxContentWidth, alignSelf: 'center' },
  row: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: Spacing.three,
    paddingVertical: Spacing.three,
    borderBottomWidth: StyleSheet.hairlineWidth,
  },
  flex: { flex: 1, gap: Spacing.one },
  right: { alignItems: 'flex-end', gap: Spacing.one },
});
