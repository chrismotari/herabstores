/**
 * Universal links (iOS) and App Links (Android): links to these paths on the
 * website open the mobile app when it's installed. Keep in sync with
 * apps/mobile/app.config.ts.
 *
 * Off until configured: set APPLE_TEAM_ID (and optionally IOS_BUNDLE_ID) for iOS,
 * ANDROID_SHA256_CERT_FINGERPRINTS (comma-separated, from `eas credentials -p android`
 * or Play Console > App signing) for Android.
 */
export const APP_LINK_PATHS = ["/products/*", "/categories/*", "/baskets/*", "/account/orders/*"];

export const IOS_APP_ID = process.env.APPLE_TEAM_ID
  ? `${process.env.APPLE_TEAM_ID}.${process.env.IOS_BUNDLE_ID || "com.herabstores.app"}`
  : null;

export const ANDROID_PACKAGE = process.env.ANDROID_PACKAGE || "com.herabstores.app";

export const ANDROID_FINGERPRINTS = (process.env.ANDROID_SHA256_CERT_FINGERPRINTS ?? "")
  .split(",")
  .map((f) => f.trim())
  .filter(Boolean);
