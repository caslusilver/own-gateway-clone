export const urlParams = new URLSearchParams(window.location.search);

export function isStatusPage() {
  const expectedPage = "woocommerce_page_wc-status";
  const expectedTab = "status";

  const tab = urlParams.get("tab");

  const isAdminPage = window.adminpage === expectedPage;
  const isStatusTab = tab === expectedTab || (!tab && expectedTab === "status");

  return isAdminPage && isStatusTab;
}

export function isGatewaySettingsPage() {
  const expectedPage = "woocommerce_page_wc-settings";
  const expectedTab = "checkout";
  const allowedSections = ["asaas-ticket", "asaas-credit-card", "asaas-pix"];

  const tab = urlParams.get("tab");
  const section = urlParams.get("section");

  return (
    window.adminpage === expectedPage &&
    tab === expectedTab &&
    allowedSections.includes(section)
  );
}

export function isCreditCardGatewaySettingsPage() {
  const expectedPage = "woocommerce_page_wc-settings";
  const expectedTab = "checkout";
  const allowedSections = ["asaas-credit-card"];

  const tab = urlParams.get("tab");
  const section = urlParams.get("section");

  return (
    window.adminpage === expectedPage &&
    tab === expectedTab &&
    allowedSections.includes(section)
  );
}
