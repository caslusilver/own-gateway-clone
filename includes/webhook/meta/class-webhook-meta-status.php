<?php
/**
 * Webhook Status Meta
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Webhook\Meta;

/**
 * Webhook Status Meta Class
 */
class Webhook_Meta_Status {
	/**
	 * The status of the connection.
	 *
	 * @var bool
	 */
	private $connection_status;

	/**
	 * The status of the queue.
	 *
	 * @var bool
	 */
	private $queue_status;

	/**
	 * The key of the connection status.
	 *
	 * @var string|bool
	 */
	private $connection_meta_key = 'asaas_connection_status';

	/**
	 * The key of the status.
	 *
	 * @var string|bool
	 */
	private $queue_meta_key = 'asaas_queue_status';

	/**
	 * Sets the status of the connection and queue.
	 *
	 * @param bool $connection_status The status of the connection. Defaults to true.
	 * @param bool $queue_status      The status of the queue. Defaults to false.
	 * @return void
	 */
	public function set_status( bool $connection_status = true, bool $queue_status = false ) {
		$this->connection_status = $connection_status;
		$this->queue_status      = $queue_status;

		$status_data = array(
			$this->connection_meta_key => $this->connection_status,
			$this->queue_meta_key      => $this->queue_status,
		);

		$this->update_status_meta( $status_data );
	}

	/**
	 * Retrieves the connection status from the status meta data.
	 *
	 * @return bool|mixed The connection status value if it exists in the status meta data, otherwise false.
	 */
	public function get_connection_status() {
		$data = $this->get_status_meta();

		if ( isset( $data[ $this->connection_meta_key ] ) ) {
			return $data[ $this->connection_meta_key ];
		}

		return false;
	}

	/**
	 * Retrieves the queue status from the status meta data.
	 *
	 * This function retrieves the queue status from the status meta data. It first calls the `get_status_meta` method
	 * to retrieve the status meta data. If the queue status is set in the data, it is returned. Otherwise, `false` is
	 * returned.
	 *
	 * @return bool The queue status if set, otherwise `false`.
	 */
	public function get_queue_status() {
		$data = $this->get_status_meta();

		if ( isset( $data[ $this->queue_meta_key ] ) ) {
			return $data[ $this->queue_meta_key ];
		}

		return false;
	}

	/**
	 * Retrieves the status meta data from the database.
	 *
	 * This function retrieves the status meta data stored in the 'asaas_status_data' option
	 * in the WordPress database. The retrieved data is then returned as the result of the
	 * function.
	 *
	 * @return array The status meta data stored in the 'asaas_status_data' option.
	 */
	private function get_status_meta() {
		$status_data = get_option( 'asaas_status_data' );

		return $status_data;
	}

	/**
	 * Updates the status meta data in the database.
	 *
	 * @param array $status_data The array of status data to be updated.
	 * @return void
	 */
	private function update_status_meta( array $status_data ) {
		update_option( 'asaas_status_data', $status_data );
	}
}
