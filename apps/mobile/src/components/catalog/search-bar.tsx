import { SymbolView } from 'expo-symbols';
import { StyleSheet, TextInput, View, type TextInputProps } from 'react-native';

import { Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';

export function SearchBar(props: TextInputProps) {
  const colors = useTheme();

  return (
    <View style={[styles.bar, { backgroundColor: colors.backgroundElement }]}>
      <SymbolView
        name={{ ios: 'magnifyingglass', android: 'search', web: 'search' }}
        size={18}
        tintColor={colors.textSecondary}
      />
      <TextInput
        placeholder="Search groceries"
        placeholderTextColor={colors.textSecondary}
        returnKeyType="search"
        autoCorrect={false}
        clearButtonMode="while-editing"
        accessibilityLabel="Search products"
        style={[styles.input, { color: colors.text }]}
        {...props}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  bar: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: Spacing.two,
    borderRadius: Spacing.five,
    paddingHorizontal: Spacing.three,
    minHeight: 44,
  },
  input: { flex: 1, fontSize: 16, minHeight: 44 },
});
