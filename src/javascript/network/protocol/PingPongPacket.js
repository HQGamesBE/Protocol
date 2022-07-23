/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

const CloudPacket = require("../CloudPacket");
/**
 * Class PingPongPacket
 * @author Jan Sohn / xxAROX
 * @date 23.07.2022 - 18:07
 * @project Protocol
 */
class PingPongPacket extends CloudPacket {
	static type = "PingPong"
	timestamp = undefined;
	received = undefined;

	/**
	 * @param {number} timestamp
	 * @param {boolean} received
	 */
	static build = (timestamp, received) => {
		const self = new PingPongPacket;
		self.timestamp = timestamp;
		self.received = received;
		return self;
	}
}
module.exports.PingPongPacket = PingPongPacket;
