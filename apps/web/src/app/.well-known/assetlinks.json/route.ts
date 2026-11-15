import { ANDROID_FINGERPRINTS, ANDROID_PACKAGE } from "@/lib/app-links";

/** Android App Links: https://developer.android.com/training/app-links/verify-android-applinks */
export function GET() {
  if (ANDROID_FINGERPRINTS.length === 0) return new Response("Not found", { status: 404 });

  return Response.json([
    {
      relation: ["delegate_permission/common.handle_all_urls", "delegate_permission/common.get_login_creds"],
      target: { namespace: "android_app", package_name: ANDROID_PACKAGE, sha256_cert_fingerprints: ANDROID_FINGERPRINTS },
    },
  ]);
}
