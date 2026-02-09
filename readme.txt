=== own-gateway-clone ===
Contributors: caslusilver
Donate link:
Tags: pix, payment, payment gateway, woocommerce, webhook
Requires at least: 4.4
Tested up to: 6.7
Requires PHP: 7.0
Stable tag: 0.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Gateway de pagamentos via PIX com webhook para WooCommerce.

== Description ==

Use PIX com webhook como metodo de pagamento no WooCommerce.

O checkout permanece transparente. O cliente nao sai da loja para finalizar o pedido. Os dados sao enviados para o webhook configurado, que processa o pagamento e retorna o status.

== Installation ==

Este gateway requer WooCommerce 2.6 ou superior.

= From your WordPress dashboard =

1. Visit _Plugins > Add New_.
1. Search for _own-gateway-clone_.
1. Click in _Install Now_ and, after, in _Activate_.
1. Visit _WooCommerce > Settings > Checkout_.
1. Configure as opcoes de pagamento e integracao do gateway.

= From WordPress.org =

1. Download _own-gateway-clone_
1. Unzip the file and upload the _own-gateway-clone_ directory to your _wp-content/plugins_ directory using your favorite method (ftp, sftp, scp, etc…). Or visit _Plugins > Add New > Upload Plugin_, select _own-gateway-clone.zip_ file, click in _Install Now_ and, after, in _Activate Plugin_.
1. Search for _own-gateway-clone_.
1. Visit _WooCommerce > Settings > Checkout_.
1. Configure as opcoes de pagamento e integracao do gateway.

== Screenshots ==

1. Checkout example
2. Pix settings

== Changelog ==

= 0.0.1 =

* Inicial: Ajuste de titularidade e base para novo gateway.
