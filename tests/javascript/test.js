const {Connect,Disconnect,Connected} = require("../../src/javascript/network/Packets").Packets;

console.log(Connect.build("My AuthToken is: 12345").jsonSerialize());
console.log(Disconnect.build("Cloud shutdown").jsonSerialize());
console.log(Connected.build((require("node:crypto")).randomBytes(16).toString("hex")).jsonSerialize());