/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

const CloudPacket = require("../CloudPacket");
/**
 * Class ConnectedPacket
 * @author Jan Sohn / xxAROX
 * @date 23.07.2022 - 17:54
 * @project Protocol
 */
class ConnectedPacket extends CloudPacket {
	static type = "Connected"
	secret = undefined;
	/**
	 * @param {string} secret
	 * @param {Language[]} languages
	 * @param {Report[]} reports
	 * @return {ConnectedPacket}
	 */
	static build = (secret, languages = [], reports = []) => {
		const self = new ConnectedPacket;
		self.secret = secret;
		self.languages = languages;
		self.reports = reports;
		return self;
	};
}
module.exports.ConnectedPacket = ConnectedPacket;
