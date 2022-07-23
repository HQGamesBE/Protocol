/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
const CloudPacket = require("../CloudPacket");
/**
 * Class UnknownPacket
 * @package
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 17:41
 * @ide PhpStorm
 * @project Protocol
 */
class UnknownPacket extends CloudPacket {
	type = "Unknown";

	static build() {
		return this;
	}
}
module.exports.UnknownPacket = UnknownPacket;