import { APP_LINK_PATHS, IOS_APP_ID } from "@/lib/app-links";

/** iOS universal links: https://developer.apple.com/documentation/xcode/supporting-associated-domains */
export function GET() {
  if (!IOS_APP_ID) return new Response("Not found", { status: 404 });

  return Response.json({
    applinks: {
      details: [{ appIDs: [IOS_APP_ID], components: APP_LINK_PATHS.map((path) => ({ "/": path })) }],
    },
    webcredentials: { apps: [IOS_APP_ID] },
  });
}
