import type { Order } from '@herabs/shared';
import { formatPrice } from '@herabs/shared';
import { useQueryClient } from '@tanstack/react-query';
import { router, useLocalSearchParams } from 'expo-router';
import { StyleSheet, View } from 'react-native';

import { OrderSummary } from '@/components/order/order-summary';
import { ThemedText } from '@/components/themed-text';
import { ThemedView } from '@/components/themed-view';
import { Button } from '@/components/ui/button';
import { FormScreen } from '@/components/ui/form-screen';
import { Spacing } from '@/constants/theme';
import { placedOrderKey } from '@/lib/cart';
import { useSession } from '@/lib/session';

export default function ConfirmationScreen() {
  const { order: number } = useLocalSearchParams<{ order: string }>();
  const order = useQueryClient().getQueryData<Order>(placedOrderKey(number));
  const { user } = useSession();

  return (
    <FormScreen>
      <ThemedView type="backgroundSelected" style={styles.banner}>
        <ThemedText type="subtitle" style={styles.title}>
          Thank you!
        </ThemedText>
        <ThemedText>
          Your order <ThemedText type="smallBold">#{number}</ThemedText> has been placed.
        </ThemedText>
        {order && (
          <ThemedText>
            Please have <ThemedText type="smallBold">{formatPrice(order.total)}</ThemedText> ready when it arrives.
          </ThemedText>
        )}
        <ThemedText type="small" themeColor="textSecondary">
          We&apos;ll call you to confirm before delivery.
        </ThemedText>
      </ThemedView>

      {order && <OrderSummary order={order} />}

      <View style={styles.actions}>
        {user && <Button title="View my orders" variant="outline" onPress={() => router.dismissTo('/account')} />}
        <Button title="Continue shopping" onPress={() => router.dismissTo('/')} />
      </View>
    </FormScreen>
  );
}

const styles = StyleSheet.create({
  banner: { padding: Spacing.four, borderRadius: Spacing.three, gap: Spacing.two },
  title: { fontSize: 26, lineHeight: 32 },
  actions: { gap: Spacing.three },
});
