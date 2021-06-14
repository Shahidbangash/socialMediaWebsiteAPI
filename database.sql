CREATE TABLE groupMember (
    groupID INT(8),
    userID INT(8),
    FOREIGN KEY(userID) REFERENCES User(userID),
    FOREIGN KEY(groupID) REFERENCES groupName(groupID)
);
CREATE TABLE groupPosts(
    postID INT(100),
    userID INT(100),
    groupID INT(100),
    postContent VARCHAR(1000),
    postDate DATE,
    likes INT(100),
    FOREIGN KEY (userID) REFERENCES User(userID),
    FOREIGN KEY (groupID) REFERENCES groupName(groupID)
);