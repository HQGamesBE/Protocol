/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

module.exports.Packets = {
	CloudPacket: require("./CloudPacket").CloudPacket,
	Unknown: require("./protocol/UnknownPacket").UnknownPacket,
	Connect: require("./protocol/ConnectPacket").ConnectPacket,
	Connected: require("./protocol/ConnectedPacket").ConnectedPacket,
	Disconnect: require("./protocol/DisconnectPacket").DisconnectPacket,
};