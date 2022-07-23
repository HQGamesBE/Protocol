/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

const CloudPacket = require("../CloudPacket");
/**
 * Class DisconnectPacket
 * @author Jan Sohn / xxAROX
 * @date 23.07.2022 - 17:51
 * @project Protocol
 */
class DisconnectPacket extends CloudPacket {
	static type = "Disconnect"
	reason = undefined;

	/**
	 * @param {string} reason
	 * @param reason
	 * @return {DisconnectPacket}
	 */
	static build = (reason) => {
		const self = new DisconnectPacket;
		self.reason = reason;
		return self;
	}
}
module.exports.DisconnectPacket = DisconnectPacket;
