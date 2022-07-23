/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

const CloudPacket = require("../CloudPacket");
/**
 * Class ConnectPacket
 * @author Jan Sohn / xxAROX
 * @date 23.07.2022 - 17:49
 * @project Protocol
 */
class ConnectPacket extends CloudPacket {
	static type = "Connect"
	AuthToken = undefined;

	static build = (AuthToken) => {
		const self = new ConnectPacket;
		self.AuthToken = AuthToken;
		return self;
	};
}
module.exports.ConnectPacket = ConnectPacket;
