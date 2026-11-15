import type { ConfigContext, ExpoConfig } from 'expo/config';

/**
 * Static settings live in app.json; this adds what depends on the environment.
 *
 * Deep links: with EXPO_PUBLIC_SITE_URL set to the https website, links to these
 * paths open the app (iOS universal links, Android App Links). The website serves
 * the matching /.well-known files (apps/web/src/lib/app-links.ts); keep the lists in sync.
 * The custom scheme (herabstores://products/...) always works.
 */
const LINK_PATHS = ['/products', '/categories', '/baskets', '/account/orders'];

export default ({ config }: ConfigContext): ExpoConfig => {
  const site = process.env.EXPO_PUBLIC_SITE_URL;
  const host = site?.startsWith('https://') ? new URL(site).host : null;

  return {
    ...config,
    name: config.name ?? 'Herab Stores',
    slug: config.slug ?? 'herab-stores',
    ios: {
      ...config.ios,
      ...(host && { associatedDomains: [`applinks:${host}`, `webcredentials:${host}`] }),
    },
    android: {
      ...config.android,
      ...(host && {
        intentFilters: [
          {
            action: 'VIEW',
            autoVerify: true,
            data: LINK_PATHS.map((pathPrefix) => ({ scheme: 'https', host, pathPrefix })),
            category: ['BROWSABLE', 'DEFAULT'],
          },
        ],
      }),
    },
    extra: {
      ...config.extra,
      // `eas init` writes the project id here (or set EAS_PROJECT_ID).
      ...(process.env.EAS_PROJECT_ID && { eas: { projectId: process.env.EAS_PROJECT_ID } }),
    },
  };
};
