import type { Order } from '@herabs/shared';
import { useQuery } from '@tanstack/react-query';
import { Stack, useLocalSearchParams } from 'expo-router';
import { RefreshControl, ScrollView, StyleSheet, View } from 'react-native';

import { OrderSummary } from '@/components/order/order-summary';
import { StatusPill } from '@/components/order/status-pill';
import { ScreenState } from '@/components/screen-state';
import { SignInPrompt } from '@/components/sign-in-prompt';
import { ThemedText } from '@/components/themed-text';
import { MaxContentWidth, Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';
import { orderQuery, STATUS_LABEL } from '@/lib/account';
import { formatDateTime } from '@/lib/format';
import { useSession } from '@/lib/session';

export default function OrderScreen() {
  const { user } = useSession();
  if (!user) return <SignInPrompt message="Sign in to see your orders." />;
  return <OrderDetail />;
}

function OrderDetail() {
  const { id } = useLocalSearchParams<{ id: string }>();
  const colors = useTheme();
  const order = useQuery(orderQuery(id));

  if (!order.data) return <ScreenState error={order.error} onRetry={() => void order.refetch()} />;
  const o = order.data;

  return (
    <ScrollView
      contentInsetAdjustmentBehavior="automatic"
      style={{ backgroundColor: colors.background }}
      contentContainerStyle={styles.content}
      refreshControl={<RefreshControl refreshing={order.isRefetching} onRefresh={() => void order.refetch()} />}>
      <Stack.Screen options={{ title: `Order #${o.order_number}` }} />
      <View style={styles.head}>
        <StatusPill status={o.status} />
        <ThemedText type="small" themeColor="textSecondary">
          Placed {formatDateTime(o.created_at)}
        </ThemedText>
      </View>
      <Timeline order={o} />
      <OrderSummary order={o} />
    </ScrollView>
  );
}

/** Received → On the way → Delivered (or Canceled). */
function Timeline({ order }: { order: Order }) {
  const colors = useTheme();

  if (order.status === 'canceled') {
    return <ThemedText themeColor="danger">This order was canceled.</ThemedText>;
  }

  const steps = [
    { status: 'new', at: order.created_at },
    { status: 'dispatched', at: order.shipped_at },
    { status: 'delivered', at: order.delivered_at },
  ] as const;
  const reached = steps.findIndex((s) => s.status === order.status);

  return (
    <View accessibilityLabel={`Order status: ${order.status ? STATUS_LABEL[order.status] : 'unknown'}`}>
      {steps.map((step, i) => {
        const done = i <= reached;
        return (
          <View key={step.status} style={styles.step}>
            <View style={styles.rail}>
              <View
                style={[styles.dot, { borderColor: colors.primary, backgroundColor: done ? colors.primary : 'transparent' }]}
              />
              {i < steps.length - 1 && (
                <View style={[styles.line, { backgroundColor: i < reached ? colors.primary : colors.border }]} />
              )}
            </View>
            <View style={styles.stepText}>
              <ThemedText type="smallBold" themeColor={done ? 'text' : 'textSecondary'}>
                {STATUS_LABEL[step.status]}
              </ThemedText>
              {done && step.at && (
                <ThemedText type="small" themeColor="textSecondary">
                  {formatDateTime(step.at)}
                </ThemedText>
              )}
            </View>
          </View>
        );
      })}
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
  head: { gap: Spacing.two },
  step: { flexDirection: 'row', gap: Spacing.three },
  rail: { alignItems: 'center', width: 16 },
  dot: { width: 16, height: 16, borderRadius: 8, borderWidth: 2, marginTop: 4 },
  line: { width: 2, flex: 1, minHeight: 24 },
  stepText: { paddingBottom: Spacing.three, gap: 2 },
});
